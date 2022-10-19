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
}