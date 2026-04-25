<?php

namespace Aphly\LaravelEmail\Commands;

use Aphly\Laravel\Models\CommonDict;
use Aphly\Laravel\Models\CommonDictValue;
use Aphly\LaravelAdmin\Models\AdminManager;
use Aphly\LaravelAdmin\Models\AdminMenu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-email:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    protected $module = 'laravel-email';


    public function handle()
    {
        AdminMenu::where('module',$this->module)->delete();
        CommonDict::where('module',$this->module)->delete();
        CommonDictValue::where('module',$this->module)->delete();

        $manager = AdminManager::where('username','admin')->firstOrError();
        $menu = AdminMenu::create(['name' => '邮件','route' =>'','pid'=>0,'uid'=>$manager->uid,'type'=>1,'module'=>$this->module,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => '站点管理','route' =>'email_admin/site/index','pid'=>$menu->id,'uid'=>$manager->uid,'type'=>2,'module'=>$this->module,'sort'=>0];
            DB::table('admin_menu')->insert($data);
        }
        $menuData = AdminMenu::where(['module'=>$this->module])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = CommonDict::create(['name' => '邮件状态','uid'=>$manager->uid,'key'=>'email_status','module'=>$this->module]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'未发送','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'已发送','value'=>'1'];
            $data[] =['dict_id' => $dict->id,'name'=>'异常','value'=>'2'];
            DB::table('common_dict_value')->insert($data);
        }

        $dict = CommonDict::create(['name' => '邮件类型','uid'=>$manager->uid,'key'=>'email_type','module'=>$this->module]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'同步','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'队列','value'=>'1'];
            DB::table('common_dict_value')->insert($data);
        }

        $dict = CommonDict::create(['name' => '队列通道','uid'=>$manager->uid,'key'=>'email_queue_priority','module'=>$this->module]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'普通','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'vip','value'=>'1'];
            DB::table('common_dict_value')->insert($data);
        }
        return 'install_ok';
    }
}
