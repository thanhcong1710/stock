<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsProcessCrawlUpdateCoPhieu68 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsProcessCrawlUpdateCoPhieu68:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JobsProcessCrawlUpdateCoPhieu68';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $list=u::query("SELECT m.ma,(SELECT ngay FROM cophieu68_data_history WHERE ma=m.ma ORDER BY ngay DESC LIMIT 1) AS ngay FROM cophieu68_ma AS m");
        foreach($list AS $row){
            $tmp = new HomeController();
            echo "/////".$row->ma."/////";
            $tmp->crawlUpdateCoPhieu68($row->ma,$row->ngay);
        }
        return "ok";
    }
    
}
