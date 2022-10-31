<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;
use PhpOffice\PhpSpreadsheet\Reader\Xls as y;
use App\Providers\UtilityServiceProvider as u;
use DOMDocument;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        echo 'Xin chào Admin, '. $user->name;
        return view('admin.dashboard');
    }
    public function import()
    {
        return view('admin.stock.import');
    }
    public function importProcess(Request $request){
        $ma = $request->ma;
        $info = pathinfo($_FILES['file']['name']);
        $ext = $info['extension']; // get the extension of the file
        $newname = $info['filename']."_".time().".".$ext; 
        $target = 'uploads/'.$newname;
        move_uploaded_file( $_FILES['file']['tmp_name'], $target);
        if($ext=='xls'){
            $reader = new y();
        }else{
            $reader = new x();
        }
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($target);
        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $dataImport = $sheet->toArray();
        $this->addItemDataImport($dataImport,$ma);
        return "ok";
    }
    private function addItemDataImport($list, $ma) {
        if ($list) {
            $query = "INSERT INTO data_history (ma, ngay, tong_gia_tri_giao_dich, tong_khoi_luong_giao_dich, von_hoa_thi_truong, gia_dong_cua, bien_dong_gia, bien_dong_phan_tram) VALUES ";
            if (count($list) > 10000) {
                for($i = 0; $i < 10000; $i++) {
                    $item = $this->convertData($list[$i]);
                    if((int)$item->tong_khoi_luong_giao_dich && $item->ngay){
                        $query.= "('$ma','$item->ngay','$item->tong_gia_tri_giao_dich','$item->tong_khoi_luong_giao_dich','$item->von_hoa_thi_truong','$item->gia_dong_cua','$item->bien_dong_gia','$item->bien_dong_phan_tram'),";
                    }
                }
                $query = substr($query, 0, -1);
                u::query($query);
                $this->addItemDataImport(array_slice($list, 10000),$ma);
            } else {
                foreach($list as $i=>$item) {
                    $item = $this->convertData($list[$i]);
                    if((int)$item->tong_khoi_luong_giao_dich && $item->ngay){
                        $query.= "('$ma','$item->ngay','$item->tong_gia_tri_giao_dich','$item->tong_khoi_luong_giao_dich','$item->von_hoa_thi_truong','$item->gia_dong_cua','$item->bien_dong_gia','$item->bien_dong_phan_tram'),";
                    }
                }
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
    private function convertData($data){
        $ngay = "";
        if($data[0]){
            if(is_numeric($data[0])){
                $unix_date = ($data[0] - 25569) * 86400;
                $ngay = date('Y-m-d',$unix_date);
                $arr_ngay = explode("-",$ngay);
                $ngay = $arr_ngay[0]."-".$arr_ngay[2]."-".$arr_ngay[1];
            }else{
                $ngay = date('Y-m-d',strtotime(str_replace("/", "-", $data[0])));
            }
        }
        $result = (object)array(
            'ngay'=> $ngay,
            'tong_khoi_luong_giao_dich'=> $data[1],
            'tong_gia_tri_giao_dich'=> $data[2],
            'von_hoa_thi_truong'=> $data[3],
            'gia_dong_cua'=> $data[4],
            'bien_dong_gia'=> $data[5] ? $data[5] : 0,
            'bien_dong_phan_tram'=> $data[6]? $data[6] : 0,
        );
        return $result;
    }
    public function crawlCoPhieu68Ma(){
        $list=u::query("SELECT * FROM cophieu68_ma WHERE id>33");
        foreach($list AS $row){
            $link="https://www.cophieu68.vn/historyprice.php?currentPage=1&id=".$row->ma;
            $data=file_get_contents($link);
            $doc = new DOMDocument();                        
            $doc->loadHTML($data,LIBXML_NOERROR);
            $content = $doc->getElementById('navigator');
            if($content){
                $list_li = $content->getElementsByTagName('li');
                $a = $list_li[count($list_li)-1]->getElementsByTagName('a');
                $page = $a[0]->getAttribute('href');
                u::updateSimpleRow(array(
                    'page'=>(int)str_replace('?currentPage=','',$page)
                ),array('id'=>$row->id),'cophieu68_ma');
            }
        }
        return "ok";
    }
    public function crawlCoPhieu68($ma,$page){
        for($i=1;$i<=$page;$i++){
            $link="https://www.cophieu68.vn/historyprice.php?currentPage=".$i."&id=".$ma;
            $data=file_get_contents($link);
            $doc = new DOMDocument();                        
            $doc->loadHTML($data,LIBXML_NOERROR);
            $content = $doc->getElementById('content');
            $list_tr = $content->getElementsByTagName('tr');

            $query = "INSERT INTO cophieu68_data_history (ma, ngay, gia_tham_chieu, bien_dong_gia, bien_dong_phan_tram, gia_dong_cua, khoi_luong, gia_mo_cua, gia_cao_nhat, gia_thap_nhat, giao_dich_thoa_thuan, nuoc_ngoai_mua, nuoc_ngoai_ban) VALUES ";
            foreach($list_tr AS $k=>$tr){
                if($k>0){
                    $list_td = $tr->getElementsByTagName('td');
                    $item = $this->convertDataCophieu68($list_td);
                    if($item){    
                        $query.=" ( '$ma', '$item->ngay', '$item->gia_tham_chieu', '$item->bien_dong_gia', '$item->bien_dong_phan_tram', '$item->gia_dong_cua', '$item->khoi_luong', '$item->gia_mo_cua', '$item->gia_cao_nhat', '$item->gia_thap_nhat', '$item->giao_dich_thoa_thuan', '$item->nuoc_ngoai_mua', '$item->nuoc_ngoai_ban'),";
                    }
                }
            };
            $query = substr($query, 0, -1);
            u::query($query);
            echo "/".$i."/";
        }
    }
    public function crawlUpdateCoPhieu68($ma,$ngay){
        $link="https://www.cophieu68.vn/historyprice.php?id=".$ma;
        $data=file_get_contents($link);
        $doc = new DOMDocument();                        
        $doc->loadHTML($data,LIBXML_NOERROR);
        $content = $doc->getElementById('content');
        if($content){
            $list_tr = $content->getElementsByTagName('tr');

            $query = "INSERT INTO cophieu68_data_history (ma, ngay, gia_tham_chieu, bien_dong_gia, bien_dong_phan_tram, gia_dong_cua, khoi_luong, gia_mo_cua, gia_cao_nhat, gia_thap_nhat, giao_dich_thoa_thuan, nuoc_ngoai_mua, nuoc_ngoai_ban) VALUES ";
            $check = 0;
            foreach($list_tr AS $k=>$tr){
                if($k>0){
                    $list_td = $tr->getElementsByTagName('td');
                    $item = $this->convertDataCophieu68($list_td);
                    if($item && $item->ngay > $ngay){    
                        $query.=" ( '$ma', '$item->ngay', '$item->gia_tham_chieu', '$item->bien_dong_gia', '$item->bien_dong_phan_tram', '$item->gia_dong_cua', '$item->khoi_luong', '$item->gia_mo_cua', '$item->gia_cao_nhat', '$item->gia_thap_nhat', '$item->giao_dich_thoa_thuan', '$item->nuoc_ngoai_mua', '$item->nuoc_ngoai_ban'),";
                        $check = 1;
                    }
                }
            };
            if($check){
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
    private function convertDataCophieu68($data){
        if(isset($data[3])){
            $bien_dong_gia = $data[3]->getElementsByTagName('span');
            $bien_dong_phan_tram = $data[4]->getElementsByTagName('span');
            $gia_dong_cua = $data[5]->getElementsByTagName('strong');
            $gia_mo_cua = $data[7]->getElementsByTagName('span');
            $gia_cao_nhat = $data[8]->getElementsByTagName('span');
            $gia_thap_nhat = $data[9]->getElementsByTagName('span');
            $result = (object)array(
                'ngay'=> date('Y-m-d',strtotime($data[1]->nodeValue)),
                'gia_tham_chieu'=> $data[2]->nodeValue,
                'bien_dong_gia'=> $bien_dong_gia[0]->nodeValue,
                'bien_dong_phan_tram'=> str_replace('%','',$bien_dong_phan_tram[0]->nodeValue),
                'gia_dong_cua'=> $gia_dong_cua[0]->nodeValue,
                'khoi_luong'=> str_replace(',','',$data[6]->nodeValue),
                'gia_mo_cua'=> $gia_mo_cua[0]->nodeValue,
                'gia_cao_nhat'=> $gia_cao_nhat[0]->nodeValue,
                'gia_thap_nhat'=> $gia_thap_nhat[0]->nodeValue,
                'giao_dich_thoa_thuan'=> str_replace(',','',$data[10]->nodeValue),
                'nuoc_ngoai_mua'=> str_replace(',','',$data[11]->nodeValue),
                'nuoc_ngoai_ban'=> str_replace(',','',$data[12]->nodeValue),
            );
            return $result;
        }else{
            return null;
        }
    }
    public function crawlCoPhieu68_Event($ma){
        $link="https://www.cophieu68.vn/eventschedule_detail.php?id=".$ma;
        $data=file_get_contents($link);
        $doc = new DOMDocument();                        
        $doc->loadHTML($data,LIBXML_NOERROR);
        $content = $doc->getElementById('events');
        $list_tr = $content->getElementsByTagName('tr');
        $check=0;
        $query = "INSERT INTO cophieu68_data_event (ma, loai_su_kien, ngay_gdkhq, ngay_thuc_hien, ti_le, ghi_chu) VALUES ";
        foreach($list_tr AS $k=>$tr){
            if($k>0){
                $list_td = $tr->getElementsByTagName('td');
                $item = $this->convertDataCophieu68Event($list_td);
                if($item){    
                    $query.=" ( '$ma', '$item->loai_su_kien', '$item->ngay_gdkhq', '$item->ngay_thuc_hien', '$item->ti_le', '$item->ghi_chu'),";
                    $check=1;
                }
            }
        };
        if($check){
            $query = substr($query, 0, -1);
            u::query($query);
        }
    }
    private function convertDataCophieu68Event($data){
        if(isset($data[4]) && preg_replace(['/\t/','/\n/','/\r/'], '', $data[4]->nodeValue)){
            $ngay_gdkhq = $data[2]->nodeValue;
            $ngay_gdkhq = explode("/",$ngay_gdkhq);
            $ngay_gdkhq = isset( $ngay_gdkhq[2]) ? $ngay_gdkhq[2]."-".$ngay_gdkhq[1]."-".$ngay_gdkhq[0] : '1975-01-01';
            $ngay_thuc_hien = $data[3]->nodeValue;
            $ngay_thuc_hien = explode("/",$ngay_thuc_hien);
            $ngay_thuc_hien = isset( $ngay_thuc_hien[2]) ? $ngay_thuc_hien[2]."-".$ngay_thuc_hien[1]."-".$ngay_thuc_hien[0] : '1975-01-01';
            $result = (object)array(
                'loai_su_kien'=> preg_replace(['/\t/','/\n/','/\r/'], '', $data[1]->nodeValue),
                'ngay_gdkhq'=> $ngay_gdkhq,
                'ngay_thuc_hien'=> $ngay_thuc_hien,
                'ti_le'=> preg_replace(['/\t/','/\n/','/\r/'], '', $data[4]->nodeValue),
                'ghi_chu'=> preg_replace(['/\t/','/\n/','/\r/'], '', $data[5]->nodeValue),
            );
            return $result;
        }else{
            return null;
        }
    }
}