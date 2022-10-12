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
}
