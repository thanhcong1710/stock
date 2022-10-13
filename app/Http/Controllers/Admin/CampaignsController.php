<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;
use PhpOffice\PhpSpreadsheet\Reader\Xls as y;
use App\Providers\UtilityServiceProvider as u;

class CampaignsController extends Controller
{
    public function list(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $page = isset($request->page) ? (int) $request->page : 1;
        $limit = isset($request->limit) ? (int) $request->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page - 1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit" : "";
        $cond = " 1 ";
        if ($keyword !== '') {
            $cond .= " AND c.title LIKE '%$keyword%'";
        }
        $total = u::first("SELECT count(id) AS total FROM campaigns AS c WHERE $cond ");
        $list = u::query("SELECT c.*
            FROM campaigns AS c WHERE $cond ORDER BY c.id DESC $limitation");

        return view('admin.campaign.list', [
            'list' => $list,
            'total' => $total->total,
            'page' => $page,
            'limit' => $limit,
            'keyword' => $keyword
        ]);
    }
    public function add(Request $request){
        return view('admin.campaign.add');
    }
    public function save(Request $request){
        u::insertSimpleRow([
            'title'=>$request->title,
            'ma'=>$request->ma,
            'start_date'=>$request->start_date,
            'rate'=>$request->rate
        ], 'campaigns');
        return redirect(route('admin.campaign.list'));
    }
    public function process(Request $request, $campaign_id){
        $type = $request->type ? $request->type : 0;
        $campaign_info = u::first("SELECT * FROM campaigns WHERE id=$campaign_id");
        u::query("DELETE FROM campaign_month WHERE campaign_id = $campaign_id");
        u::query("DELETE FROM campaign_cycles WHERE campaign_id = $campaign_id");
        if($type==0){
            $list_month = u::query("SELECT * FROM data_history_month WHERE month>='".date('Y-m',strtotime($campaign_info->start_date))."'");
            foreach($list_month AS $row){
                $num = floor($campaign_info->amount_max/(100*$row->gia_trung_binh));
                u::insertSimpleRow([
                    'campaign_id'=>$campaign_id,
                    'month'=>$row->month,
                    'type'=>0,
                    'num'=>$num*100,
                    'amount_disbursement'=>$row->gia_trung_binh*$num*100,
                    'gia_mua'=>$row->gia_trung_binh
                ], 'campaign_month');
            }
            $data_history = u::query("SELECT * FROM data_history WHERE ma='$campaign_info->ma' AND ngay>='$campaign_info->start_date' ORDER BY ngay");
            $start_date = $campaign_info->start_date;
            foreach($data_history AS $his){
                $data_info = u::first("SELECT SUM(num) AS num, SUM(amount_disbursement) AS amount_disbursement,COUNT(id) AS count_month  FROM campaign_month 
                    WHERE month>='".date('Y-m',strtotime($start_date))."' AND month <= '".date('Y-m',strtotime($his->ngay))."' AND campaign_id= $campaign_id");
                $tmp_amount = $data_info->num *$his->gia_dong_cua;
                if($data_info->count_month>1 && $tmp_amount > $data_info->amount_disbursement*(100+$campaign_info->rate)/100){
                    u::insertSimpleRow([
                        'campaign_id'=>$campaign_id,
                        'type'=>0,
                        'start_date'=>$start_date,
                        'end_date'=>$his->ngay,
                        'count_month'=>$data_info->count_month,
                        'amount_disbursement'=>$data_info->amount_disbursement,
                        'amount_total'=>$data_info->amount_disbursement*(100+$campaign_info->rate)/100,
                        'gia_dong_cua'=>$his->gia_dong_cua
                    ], 'campaign_cycles');
                    $start_date = $his->ngay;
                }
            }
            return "ok";
        }

    }
    public function processDataHistoryMonth($ma){
        $list_month = u::query("SELECT DISTINCT DATE_FORMAT(ngay,'%Y-%m') AS report_month FROM data_history WHERE ma='$ma' ORDER BY report_month ");
        foreach($list_month AS $row){
            $gia_thap_nhat=u::first("SELECT MIN(gia_dong_cua) AS gia_dong_cua FROM data_history WHERE ma='$ma' AND DATE_FORMAT(ngay,'%Y-%m')='$row->report_month'");
            $gia_cao_nhat=u::first("SELECT MAX(gia_dong_cua) AS gia_dong_cua FROM data_history WHERE ma='$ma' AND DATE_FORMAT(ngay,'%Y-%m')='$row->report_month'");
            $gia_trung_binh=u::first("SELECT SUM(gia_dong_cua) AS total_gia_dong_cua,COUNT(id) AS total FROM data_history WHERE ma='$ma' AND DATE_FORMAT(ngay,'%Y-%m')='$row->report_month'");
            u::insertSimpleRow([
                'ma'=>$ma,
                'month'=>$row->report_month,
                'gia_thap_nhat'=>$gia_thap_nhat->gia_dong_cua,
                'gia_cao_nhat'=>$gia_cao_nhat->gia_dong_cua,
                'gia_trung_binh'=>ceil($gia_trung_binh->total_gia_dong_cua/$gia_trung_binh->total),
            ], 'data_history_month');
        }
        return "ok";
    }
}
