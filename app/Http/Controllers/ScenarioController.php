<?php

namespace App\Http\Controllers;

use App\Models\Biz\DtMailSchedule;
use App\Models\Df\DtMailSchedule as DtMailScheduleDf;
use App\Models\Biz\DtMailSetting;
use App\Models\Df\DtMailSetting as DtMailSettingDf;
use App\Models\Biz\MstPostal;
use App\Models\Df\MstPostal as MstPostalDf;
use App\Models\Biz\MstStandardDate;
use App\Models\Df\MstStandardDate as MstStandardDateDf;
use App\Models\Biz\OcProduct;
use App\Models\Df\OcProduct as OcProductDf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScenarioController extends Controller
{
    private $mPostal;
    private $mMailSetting;
    private $mProduct;
    private $mStandardDate;
    private $mMailSchedule;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(session('mall') == 'df') {
                $this->mPostal = new MstPostalDf();
                $this->mMailSetting = new DtMailSettingDf();
                $this->mProduct = new OcProductDf();
                $this->mStandardDate = new MstStandardDateDf();
                $this->mMailSchedule = new DtMailScheduleDf();
            } else {
                $this->mPostal = new MstPostal();
                $this->mMailSetting = new DtMailSetting();
                $this->mProduct = new OcProduct();
                $this->mStandardDate = new MstStandardDate();
                $this->mMailSchedule = new DtMailSchedule();
            }
            return $next($request);
        });
    }
    /**
     * View screen scenario setting
     *
     * Return view, show info setting
     * @return view
     */
    public function index()
    {
        return view("admin.scenario.index");
    }

    /**
     * Process get data scenario setting
     *
     * @param Request $request prama condition get data
     * @return json
     */
    public function getData(Request $request)
    {
        $limit = $request->get('limit');
        if (!is_numeric($limit)) {
            $limit = 25;
        }
        $datas = $this->mMailSetting->getDatas($request->all(), $limit);
        $result = [];
        foreach ($datas as $item) {
            if ($item->setting_status == 1) {
                $item->status = true;
            } else {
                $item->status = false;
            }
            $item->flg_delete = true;
            if (in_array($item->standard_id, [1, 2, 3])) {
                $item->flg_delete = false;
            }
            $result[] = $item;
        }
        $datas->data = $result;
        // dd($datas);
        return response()->json($datas);
    }

    /**
     * View screen page add scenario send scenario
     * @return view
     */
    public function viewAdd()
    {
        $urlPrevious = url()->previous();
        if (!\Str::contains($urlPrevious, ['scenario', 'schedule']) || \Str::contains($urlPrevious, ['scenario/add', 'scenario/edit'])) {
            $urlPrevious = route('scenario');
        }
        $prefectures = $this->mPostal->getListPrefecture();
        $listStandard = $this->mStandardDate->get();
        $rank = request()->get('rank');
        $strRank = '';
        if (!empty($rank)) {
            $strRank = $rank;
            if ($rank > 5) {
                $strRank .= "," . ($rank - 5);
            } else {
                $strRank .= "," . ($rank + 5);
            }
        }
        return view(
            "admin.scenario.scenario-add",
            [
                'prefectures'  => $prefectures,
                'standardDate' => $listStandard,
                'edit'         => false,
                'mail_type'    => 1,
                'strRank'      => $strRank,
                'urlPrevious'  => $urlPrevious,
            ]
        );
    }

    /**
     * View screen page add scenario send scenario
     * @return view
     */
    public function viewAddSpot()
    {
        $urlPrevious = url()->previous();
        if (!\Str::contains($urlPrevious, ['scenario', 'schedule']) || \Str::contains($urlPrevious, ['scenario/add', 'scenario/edit'])) {
            $urlPrevious = route('scenario');
        }
        $prefectures = $this->mPostal->getListPrefecture();
        $listStandard = $this->mStandardDate->get();
        $rank = request()->get('rank');
        $strRank = '';
        if (!empty($rank)) {
            $strRank = $rank;
            if ($rank > 5) {
                $strRank .= "," . ($rank - 5);
            } else {
                $strRank .= "," . ($rank + 5);
            }
        }
        return view(
            "admin.scenario.scenario-add",
            [
                'prefectures'  => $prefectures,
                'standardDate' => $listStandard,
                'mail_type'    => 2,
                'strRank'      => $strRank,
                'urlPrevious'  => $urlPrevious,
            ]
        );
    }

    /**
     * View screen page edit scenario send scenario
     * @return view
     */
    public function viewEdit($id)
    {
        $urlPrevious = url()->previous();
        if (!\Str::contains($urlPrevious, ['scenario', 'schedule']) || \Str::contains($urlPrevious, ['scenario/add', 'scenario/edit'])) {
            $urlPrevious = route('scenario');
        }
        $paramGetMaillSetting = [
            ['mail_setting_id', $id],
        ];

        $prefectures = $this->mPostal->getListPrefecture();
        $listStandard = $this->mStandardDate->get();

        $mailSetting = $this->mMailSetting->getData($paramGetMaillSetting)->toArray();

        if (empty($mailSetting)) {
            return redirect()->route('scenario')->with('error', '空データです。');
        }

        $paranGetSchedule = [
            ['mail_setting_id', $id],
        ];
        $mailSetting['schedules'] = $this->mMailSchedule->getDataEditMailSetting($paranGetSchedule)->toArray();

        if (!empty($mailSetting['product_specify'])) {
            $mailSetting['product_specify'] = $this->mProduct
                ->getDataEditMailSetting([['oc_product.model', $mailSetting['product_specify']]])
                ->toArray();
        } else {
            $mailSetting['product_specify']          = [];
            $mailSetting['product_specify']['model'] = "";
            $mailSetting['product_specify']['name']  = "";
        }
        if ($mailSetting['setting_status'] == 1) {
            $mailSetting['status'] = true;
        } else {
            $mailSetting['status'] = false;
        }
        $mailSetting['flg_standard'] = 0;
        if (in_array($mailSetting['standard_id'], [1, 2, 3])) {
            $mailSetting['flg_standard'] = 1;
        }
        return view(
            "admin.scenario.scenario-edit",
            [
                'prefectures'  => $prefectures,
                'standardDate' => $listStandard,
                'data'         => $mailSetting,
                'urlPrevious'  => $urlPrevious,
            ]
        );
    }

    /**
     * Process save data
     *
     * @param request $request parameter data need save
     * @return json
     */
    public function save(Request $request)
    {
        $data = $request->all();
        $arrSchedule = $data["schedules"] ?? [];
        $arrScheduleRemove = $data["schedules_remove"] ?? [];
        $idOpe = Auth::user()->id;
        $result = [
            'status'   => 0,
            'arrError' => [],
            'message'  => [],
            'id'       => '',
        ];
        //validate data
        if (empty($data['setting_name'])) {
            $result['status']     = 1;
            $result['arrError']['setting_name'] = true;
            $result['message']['setting_name']  = "設定名称を入力してください";
        }
        //if standar_id = 5 then not validate infor schedule
        if (!empty($arrSchedule)) {
            foreach ($arrSchedule as $item) {
                if (empty($item['schedule_name'])) {
                    $result['status']                    = 1;
                    $result['arrError']['schedule-edit'] = true;
                    $result['message']['schedule_name']  = " 管理用スケジュール名を入力してください";
                    $result['arrScheduleError'][$item['schedule_id_html']]['schedule_name'] = true;
                }
                if ((!isset($item['date_num']) || !is_numeric($item['date_num'])) && $data['mail_type'] == 1) {
                    $result['status']                    = 1;
                    $result['arrError']['schedule-edit'] = true;
                    $result['message']['date_num']       = "スケジュール設定で配信日時を指定してください";
                    $result['arrScheduleError'][$item['schedule_id_html']]['date_num'] = true;
                }
                if (!isset($item['hour']) || $item['hour'] == '' || !is_numeric($item['hour'])) {
                    $result['status']                    = 1;
                    $result['arrError']['schedule-edit'] = true;
                    $result['message']['hour']           = "Required: select hour send mail";
                    $result['arrScheduleError'][$item['schedule_id_html']]['hour'] = true;
                }
                if (empty($item['date']) && $data['mail_type'] == 2) {
                    $result['status']                    = 1;
                    $result['arrError']['schedule-edit'] = true;
                    $result['message']['date']           = "Required: select date send mail";
                    $result['arrScheduleError'][$item['schedule_id_html']]['date'] = true;
                }
            }
        }
        if (empty($data['receive_property'])) {
            $result['status']     = 1;
            $result['message'][]    = "設定名称:  最低1つ以上選択してください";
        }

        if ($result['status'] == 0) {
            if (!empty($data['mail_setting_id'])) {
                $mailSetting = $this->mMailSetting->find($data['mail_setting_id']);
                $mailSetting->up_ope_cd        = $idOpe;
            } else {
                // created new scenario send mail
                $mailSetting = $this->mMailSetting;
                $mailSetting->in_ope_cd        = $idOpe;
                $mailSetting->mail_type        = $data['mail_type'];
            }

            // save mail setting
            $mailSetting->setting_name     = $data['setting_name'];
            $mailSetting->remarks          = $data['remarks'];
            $mailSetting->receive_property = implode(",", $data['receive_property'] ?? []);
            $mailSetting->customer_target  = implode(",", $data['customer_target'] ?? []);
            $mailSetting->standard_id      = $data['standard_id'];
            $mailSetting->product_specify  = $data['product_specify']['model'] ?? '';

            $mailSetting->save();

            $idMailSetting = $mailSetting->mail_setting_id;
            $dataInsertShedule = [];
            $isNotTemplate = false;
            $offSchedule = true;
            //nếu không có schedule hoặc có 1 schedule và schudule đó bị tắt thì không cho bật scenario
            if (empty($arrSchedule)) {
                $isNotTemplate = true;
                $offSchedule = false;
            }

            foreach ($arrSchedule as $schedule) {
                if (!empty($schedule['schedule_id'])) {
                    $item = $this->mMailSchedule->find($schedule['schedule_id']);
                    $item->mail_setting_id = $idMailSetting;
                    $item->schedule_name   = $schedule['schedule_name'];
                    $item->schedule_status = $schedule['schedule_status'];
                    $item->date_num        = $schedule['date_num'] ?? 0;
                    $item->is_after        = $schedule['is_after'] ?? null;
                    $item->hour            = $schedule['hour'];
                    $item->date            = $schedule['date'] ?? '';
                    $item->minute          = $schedule['minute'];
                    $item->up_ope_cd       = $idOpe;
                    $item->save();
                    if (empty($item->mail_template_id) && $schedule['schedule_status'] == 1) {
                        $isNotTemplate = true;
                    }
                    if ($schedule['schedule_status'] == 1) {
                        $offSchedule = false;
                    }
                } else {
                    $dataInsertShedule[] = [
                        'mail_setting_id' => $idMailSetting,
                        'schedule_name'   => $schedule['schedule_name'],
                        'schedule_status' => $schedule['schedule_status'],
                        'date_num'        => $schedule['date_num'] ?? 0,
                        'is_after'        => $schedule['is_after'] ?? null,
                        'hour'            => $schedule['hour'],
                        'date'            => $schedule['date'] ?? '',
                        'minute'          => $schedule['minute'],
                        'in_date'         => now(),
                        'up_date'         => now(),
                        'in_ope_cd'       => $idOpe,
                    ];
                    $isNotTemplate =  true;
                }
            }
            if (!empty($dataInsertShedule)) {
                $this->mMailSchedule->insert($dataInsertShedule);
            }
            foreach ($arrScheduleRemove as $schedule) {
                if (!empty($schedule['schedule_id'])) {
                    $item = $this->mMailSchedule->find($schedule['schedule_id']);
                    $item->delete();
                }
            }
            //Set status scenario
            // Nếu có schedule mới hoặc có schedule chưa trọn template mail hoặc không có schedule thì set setting_status = 0
            if ($isNotTemplate || $offSchedule) {
                $mailSetting->setting_status = 0;
            } else {
                if (!empty($data['mail_setting_id'])) {
                    $mailSetting->setting_status = $data['setting_status'];
                } else {
                    $mailSetting->setting_status = 0;
                }
            }
            $mailSetting->save();
            $result['message'] = "Success";
            $result['id'] = $idMailSetting;
        }
        return response()->json($result);
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
        $data =  $this->mMailSetting->find($id)->replicate()->fill([
            'in_ope_cd' => $inOpeCd,
            'up_ope_cd' => null,
        ]);
        $listSchedule = $this->mMailSchedule->where('mail_setting_id', $id)->get();
        $dataInsertShedule = [];

        if ($data->save()) {
            $mailSettingID = $data->mail_setting_id;
            foreach ($listSchedule as $item) {
                $dataInsertShedule[] = [
                    'mail_setting_id' => $mailSettingID,
                    'schedule_name'   => $item['schedule_name'],
                    'schedule_status' => $item['schedule_status'],
                    'date_num'        => $item['date_num'],
                    'is_after'        => $item['is_after'],
                    'hour'            => $item['hour'],
                    'minute'          => $item['minute'],
                    'in_date'         => now(),
                    'up_date'         => now(),
                    'in_ope_cd'       => $inOpeCd,
                ];
            }
            if (!empty($dataInsertShedule)) {
                $this->mMailSchedule->insert($dataInsertShedule);
            }
            return response()->json(['success' => "Success",]);
        } else {
            return response()->json(['error' => 'Error copy scenario']);
        }
    }

    /**
     * Process delete email setting
     *
     * @param int $id id element need delete
     * @return json
     */
    public function delete($id)
    {
        $item = $this->mMailSetting->find($id);
        if (empty($item)) {
            return response()->json(['error' => 'Item not found']);
        }
        $listSchedule = $this->mMailSchedule->where('mail_setting_id', $item->mail_setting_id)->delete();
        if ($item->delete()) {
            return response()->json(['success' => "Success",]);
        } else {
            return response()->json(['error' => 'Error delete scenario']);
        }
    }

    /**
     * Process search product
     *
     * @param Request $request param condition search
     * @return json
     */
    public function searchProduct(Request $request)
    {
        $param = $request->all();
        $datas = $this->mProduct->getDatas($param);
        return response()->json($datas);
    }

    /**
     * Process change status mail setting
     *
     * @param Request $request id mail setting need change status
     * @return json
     */
    public function changeStatusMailSeting(Request $request)
    {
        $inOpeCd = Auth::user()->id;
        $param   = $request->all();
        if (empty($param['mail_setting_id'])) {
            return response()->json([
                'status'  => 1,
                'message' => 'Not found setting',
            ]);
        }
        $item = $this->mMailSetting->find($param['mail_setting_id']);
        $schedules = $this->mMailSchedule
            ->where('mail_setting_id', $item->mail_setting_id)->get()->toArray();
        if (count($schedules) == 0) {
            return response()->json(['error' => 'Error scenario not has schedule']);
        }
        //Tracking select mail template schedule
        $allSelectTemplate = true;
        foreach ($schedules as $schedule) {
            if (empty($schedule['mail_template_id']) && $schedule['schedule_status'] == 1) {
                $allSelectTemplate = false;
                break;
            }
        }
        if (!$allSelectTemplate) {
            return response()->json(['error' => 'Please select mail template']);
        }

        $item->setting_status = ($item->setting_status == 1) ? 0 : 1;
        $item->up_ope_cd      = $inOpeCd;
        if ($item->save()) {
            return response()->json(['success' => "Success",]);
        } else {
            return response()->json(['error' => 'Error change status scenario']);
        }
    }
}
