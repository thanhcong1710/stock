<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;
use PhpOffice\PhpSpreadsheet\Reader\Xls as y;
use App\Providers\UtilityServiceProvider as u;

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
}