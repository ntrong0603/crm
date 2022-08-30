<?php

namespace App\Http\Controllers;

use App\Models\Biz\DtMailTemplate;
use App\Models\Df\DtMailTemplate as DtMailTemplateDf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\CustomMailable;
use Illuminate\Support\Facades\Mail;

class MailTemplateController extends Controller
{
    private $mMaillTemplate;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mMaillTemplate = new DtMailTemplateDf();
            } else {
                $this->mMaillTemplate = new DtMailTemplate();
            }
            return $next($request);
        });
    }
    /**
     * View screen mail template
     *
     * Return view, show info template
     * @return view
     */
    public function index()
    {
        return view("admin.mail-template.index", ['prefectures' => []]);
    }

    /**
     * View screen page created new mail template
     *
     * @return view
     */
    public function viewAdd()
    {
        return view("admin.mail-template.mail-template-add");
    }

    /**
     * View screen page edit mail template
     *
     * @return view
     */
    public function viewEdit($id)
    {
        $template = $this->mMaillTemplate->find($id);
        if (empty($template)) {
            return redirect()->route('mail-template')->with('error', '空データです。');
        }

        $tag_regex = '/<table [^>]*>(.*?)<\\/body>/s';
        preg_match(
            $tag_regex,
            $template->mail_content_html,
            $matches
        );
        if (!empty($matches)) {
            $template->body = $matches[0];
        }
        return view("admin.mail-template.mail-template-edit", ['template' => $template]);
    }

    /**
     * Process save data
     *
     * @param request $request parameter data need save
     * @return json
     */
    public function save(Request $request)
    {
        $rule = [
            'template_name' =>  'required|min:3',
        ];
        $notify = [];
        $request->validate($rule, $notify);

        $data = $request->all();
        $idOpe = Auth::user()->id;
        if (!empty($data['mail_template_id'])) {
            $mail = $this->mMaillTemplate->find($data['mail_template_id']);
            $mail->up_ope_cd = $idOpe;
        } else {
            $mail = $this->mMaillTemplate;
            $mail->in_ope_cd = $idOpe;
        }
        $mail->template_name     = $data['template_name'];

        // if (!empty($data['mail_template_id']) && $mail->is_protected == 0) {
        //     $mail->mail_content_html = $data['mail_content_html'];
        //     $mail->mail_content_text = $data['mail_content_text'];
        // } elseif (empty($data['mail_template_id'])) {
        $mail->mail_content_html = $data['mail_content_html'];
        $mail->mail_content_text = $data['mail_content_text'];
        // }
        if ($mail->save()) {
            return [
                'status' => 0,
                'id' => $mail->mail_template_id,
                'message' => "Save success",
            ];
        } else {
            return [
                'status' => 1,
                'message' => "Error process save!",
            ];
        }
    }

    /**
     * Process send mail test
     *
     * @param request $request parameter data need save
     * @return json
     */
    public function sendMailTest(Request $request)
    {
        $rule = [
            'template_name' =>  'required|min:3',
            'test_send_to'  =>  'required',
        ];
        $notify = [];
        $request->validate($rule, $notify);

        $datas = $request->all();
        try {
            $sendMail = Mail::to($datas['test_send_to']);
            $content = '';
            if ($datas['send_type'] == 1) {
                $content = $datas['mail_content_html'];
            } else {
                $content = preg_replace("/\r|\n/", "<br>", $datas['mail_content_text']);
            }
            $arrParam = [
                '[shop_name]'                => 'DIY FACTORY (B向け本店)',
                '[shop_address]'             => $data['shop_address'] ?? '',
                '[shop_shop_url]'            => 'https://shop.diyfactory.jp',
                '[shop_tel]'                 => '',
                '[customer_org_id]'          => 1,
                '[customer_name]'            => 'DAITO 1',
                '[customer_last_name]'       => 'DAITO 2',
                '[customer_first_name]'      => 'DAITO 3',
                '[customer_last_name_kana]'  => 'DAITO kana 4',
                '[customer_first_name_kana]' => 'DAITO kana 5',
                '[customer_sex]'             => 0,
                '[customer_sex:format]'      => '',
                '[customer_birthday]'        => '2020-01-01',
                '[customer_email]'           => 'customer@monotos.biz',
                '[customer_email_mobile]'    => '090-123-4567',
                '[customer_fax]'             => '090-123-4567',
                '[customer_zip]'             => '1250032',
                '[data-body]'                => 'DATA BODY',
                '[data-token]'               => 'DATA TOKEN',
            ];

            foreach ($arrParam as $key => $v) {
                $content = str_replace($key, $v, $content);
            }
            $sendMail->send(new CustomMailable(false, $content, $datas['template_name']));
            return [
                'status' => 1,
                'message' => "Send success !",
            ];
        } catch (Exception $e) {
            return [
                'status' => -1,
                'message' => "Send fail !",
            ];
        }
    }

    /**
     * Process convert from design template mail to template mail
     *
     * @param request $request
     * @return json
     */
    public function convert(Request $request)
    {
        $data = $request->all();
        if (!empty($data['send_type']) && $data['send_type'] == 1) {
            $html = view('admin.mail-template.preview-template', compact('data'))->render();
            return response()->json($this->indentContent(html_entity_decode($html)));
        }
        if (!empty($data['send_type']) && $data['send_type'] == 2) {
            $html = view('admin.mail-template.preview-template', compact('data'))->render();
            return response()->json($data['body']);
        }
    }

    /**
     * Process review template email
     */
    public function review(Request $request)
    {
        $data = $request->session()->get('dataProvisional');
        return view('admin.mail-template.preview-template', ['data' => $data]);
    }

    /**
     * function set data design
     */
    public function saveProvisional(Request $request)
    {
        $request->session()->forget('dataProvisional');
        $request->session()->put("dataProvisional", $request->all());
        return;
    }

    /**
     * Function format content html
     */
    public function indentContent($content, $tab = "\t")
    {

        // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
        $content = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $content);

        // now indent the tags
        $token = strtok($content, "\n");
        $result = ''; // holds formatted version as it is built
        $pad = 0; // initial indent
        $matches = array(); // returns from preg_matches()

        // scan each line and adjust indent based on opening/closing tags
        while ($token !== false) {
            $token = trim($token);
            $indent = 0;
            // test for the various tag states
            if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) {
                // 1. open and closing tags on same line - no change
                $indent = 0;
            } elseif (preg_match('/^<\/\w/', $token, $matches)) {
                // 2. closing tag - outdent now
                $pad--;
                if ($indent > 0) {
                    $indent = 0;
                }
            } elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) {
                // 3. opening tag - don't pad this one, only subsequent tags
                $indent = 1;
            } else {
                // 4. no indentation needed
                $indent = 0;
            }

            // pad the line with the required number of leading spaces
            $line = str_pad($token, strlen($token) + $pad, $tab, STR_PAD_LEFT);
            $result .= $line . "\n"; // add to the cumulative result, with linefeed
            $token = strtok("\n"); // get the next token
            $pad += $indent; // update the pad size for subsequent lines
        }

        return $result;
    }

    /**
     * Function get list mail setting
     */
    public function getData(Request $request)
    {
        $limit = $request->get('perPage');
        if (!is_numeric($limit)) {
            $limit = 25;
        }
        $datas = $this->mMaillTemplate->getDatas($request->all(), $limit);
        return response()->json($datas);
    }

    /**
     * Get data template
     *
     * @param Request $request condition get info one template
     * @return json
     */
    public function getTemplate(Request $request)
    {
        if ($request->ajax() === true) {
            $param = $request->all();
            $template = $this->mMaillTemplate->getData($param);
            if (empty($template) || !$template) {
                return response()->json([
                    "error" => 1,
                    "message" => "空データです。",
                ]);
            }

            $tag_regex = '/<table [^>]*>(.*?)<\\/body>/s';
            preg_match(
                $tag_regex,
                $template->mail_content_html,
                $matches
            );
            if (!empty($matches)) {
                $template->body = $matches[0];
            }
            return response()->json($template);
        }
        return false;
    }

    /**
     * Process remove mail template
     */
    public function delete($id)
    {
        $item = $this->mMaillTemplate->find($id);
        if (empty($item)) {
            return response()->json(['error' => 'Item not found']);
        }
        if ($item->is_protected == 0) {
            if ($item->delete()) {
                return response()->json(['success' => "Success",]);
            } else {
                return response()->json(['error' => 'Error delete mail template']);
            }
        } else {
            return response()->json(['error' => 'Template not remove']);
        }
    }

    /**
     * Process copy email setting
     *
     * @param int $id id element need copy
     * @return json
     */
    public function copy($id)
    {
        $inOpeCd = Auth::user()->id;
        $data =  $this->mMaillTemplate->find($id)->replicate()->fill([
            'in_ope_cd' => $inOpeCd,
            'up_ope_cd' => $inOpeCd,
        ]);
        if ($data->is_protected == 0) {
            if ($data->save()) {
                return response()->json(['success' => "success",]);
            } else {
                return response()->json(['error' => 'Error copy mail template']);
            }
        } else {
            return response()->json(['error' => 'Template not copy']);
        }
    }
}
