<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\HomeController;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsProcessCrawlCoPhieu68 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsProcessCrawlCoPhieu68:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JobsProcessCrawlCoPhieu68';

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
        $list=u::query("SELECT * FROM cophieu68_ma WHERE status=0");
        foreach($list AS $row){
            $tmp = new HomeController();
            echo "/////".$row->ma."/////";
            $tmp->crawlCoPhieu68($row->ma,$row->page);
            u::updateSimpleRow(['status'=>1],['id'=>$row->id],'cophieu68_ma');
            echo "/////".$row->ma."/////";
        }
        return "ok";
    }
    
}
