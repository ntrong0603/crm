<?php

namespace App\Http\Controllers;

use App\Models\Biz\DtMailSchedule;
use App\Models\Df\DtMailSchedule as DtMailScheduleDf;
use App\Models\Biz\DtMailSetting;
use App\Models\Df\DtMailSetting as DtMailSettingDf;
use App\Models\Biz\DtMailTemplate;
use App\Models\Df\DtMailTemplate as DtMailTemplateDf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    private $mSchedule;
    private $mMaillTemplate;
    private $mMailSetting;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mSchedule = new DtMailScheduleDf();
                $this->mMailSetting = new DtMailSettingDf();
                $this->mMaillTemplate = new DtMailTemplateDf();
            } else {
                $this->mSchedule = new DtMailSchedule();
                $this->mMailSetting = new DtMailSetting();
                $this->mMaillTemplate = new DtMailTemplate();
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view("admin.schedule.index");
    }

    /**
     * Get list data schedule
     *
     * @param \Illuminate\Http\Request $request condition get data
     * @return \Illuminate\Http\Response
     */
    public function getListData(Request $request)
    {
        $limit = $request->get('limit');
        if (!is_numeric($limit)) {
            $limit = 25;
        }
        $datas = $this->mSchedule->getDatas($request->all(), $limit);
        $result = [];
        foreach ($datas as $item) {
            if ($item->setting_status == 0 || $item->schedule_status == 0) {
                $item->status = "停止中";
            } else {
                $item->status = "有効中";
            }
            if ($item->mail_type == 1) {
                $item->mail_type_title = "シナリオメール";
            } else {
                $item->mail_type_title = "スポットメール";
            }
            $result[] = $item;
        }
        $datas->data = $result;
        return response()->json($datas);
    }

    /**
     * View edit schedule
     *
     * @param int $id id schedule need edit
     * @return \Illuminate\Http\Response
     */
    public function viewEdit($id)
    {
        $listMailTemplate = $this->mMaillTemplate->get();

        $schedule = $this->mSchedule->getData(["schedule_id" => $id]);
        if (empty($schedule)) {
            return redirect()->route('schedule')->with('error', '空データです。');
        }

        $mailSetting = $this->mMailSetting->select(["setting_status"])->find($schedule->mail_setting_id);
        if ($mailSetting->setting_status == 0 || $schedule->schedule_status == 0) {
            $schedule->schedule_action = "0";
        } else {
            $schedule->schedule_action = "1";
        }
        $template = $this->mMaillTemplate->find($schedule->mail_template_id);
        if (!empty($template)) {
            $tag_regex = '/<table [^>]*>(.*?)<\\/body>/s';
            preg_match(
                $tag_regex,
                $template->mail_content_html,
                $matches
            );
            if (!empty($matches)) {
                $template->body = $matches[0];
            }
        }
        return view("admin.mail-template.mail-template-edit", [
            "editSchedule" => 1,
            "template"     => $template,
            "schedule"     => $schedule,
            "listMail"     => $listMailTemplate,
        ]);
    }

    /**
     * Get list data schedule
     *
     * @param \Illuminate\Http\Request $request condition get data
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $arrRule = [
            'schedule_id'      => 'required',
            'subject'          => 'required|min:3',
            'mail_from'        => 'required|min:10|email|email:rfc,dns',
        ];
        $arrNoti = [];
        $request->validate($arrRule, $arrNoti);

        $idOpe = Auth::user()->id;
        $data = $request->all();

        $schedule = $this->mSchedule->find($data['schedule_id']);
        $schedule->mail_from            = $data['mail_from'];
        $schedule->mail_from_name       = $data['mail_from_name'];
        $schedule->mail_template_option = $data['mail_template_option'];
        $schedule->link_unsubscribe     = $data['link_unsubscribe'];
        $schedule->subject              = $data['subject'];
        $schedule->up_ope_cd            = $idOpe;

        $saveTemplate = false;
        //Create template mail
        if ($data['save_design'] == "true") {
            $mailTemplate = $this->mMaillTemplate;
            $mailTemplate->in_ope_cd = $idOpe;
            $mailTemplate->mail_content_html = $data['mail_content_html'];
            $mailTemplate->mail_content_text = $data['mail_content_text'];
            $mailTemplate->template_name     = $data['subject'];
            $mailTemplate->is_protected      = 0;
            $saveTemplate = true;
        } elseif (!empty($data['mail_template_id'])) {
            // edit infor template new
            $mailTemplate            = $this->mMaillTemplate->find($data['mail_template_id']);
            $mailTemplate->up_ope_cd = $idOpe;
            $mailTemplate->template_name     = $data['template_name'];
            // if ($mailTemplate->is_protected == 0) {
            $mailTemplate->mail_content_html = $data['mail_content_html'];
            $mailTemplate->mail_content_text = $data['mail_content_text'];
            // }
            $saveTemplate = true;
        }
        if ($saveTemplate) {
            if ($mailTemplate->save()) {
                $schedule->mail_template_id     = $mailTemplate->mail_template_id;
            } else {
                return [
                    'status' => 1,
                    'message' => "Error process save template mail!",
                ];
            }
        }

        //Nếu lưu thông tin schedule thành công và template email được phép chỉnh sửa thiết kế thì thay đổi thông tin template email
        if ($schedule->save()) {
            return response()->json([
                'status' => 0,
                'message' => 'Save success',
                'id' => $schedule->schedule_id,
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'message' => 'Error save schedule',
            ]);
        }
    }
}
