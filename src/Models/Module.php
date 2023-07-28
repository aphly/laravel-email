<?php

namespace Aphly\LaravelEmail\Models;

use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Menu;
use Aphly\Laravel\Models\Module as Module_base;
use Illuminate\Support\Facades\DB;

class Module extends Module_base
{
    public $dir = __DIR__;

    public function install($module_id){
        parent::install($module_id);
        $manager = Manager::where('username','admin')->firstOrError();
        $menu = Menu::create(['name' => '邮件','route' =>'','pid'=>0,'uuid'=>$manager->uuid,'type'=>1,'module_id'=>$module_id,'sort'=>10]);
        if($menu->id){
            $data=[];
            $data[] =['name' => '邮件测试','route' =>'email_admin/email/test','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            $data[] =['name' => '站点管理','route' =>'email_admin/site/index','pid'=>$menu->id,'uuid'=>$manager->uuid,'type'=>2,'module_id'=>$module_id,'sort'=>0];
            DB::table('admin_menu')->insert($data);
        }
        $menuData = Menu::where(['module_id'=>$module_id])->get();
        $data=[];
        foreach ($menuData as $val){
            $data[] =['role_id' => 1,'menu_id'=>$val->id];
        }
        DB::table('admin_role_menu')->insert($data);

        $dict = Dict::create(['name' => '邮件状态','uuid'=>$manager->uuid,'key'=>'email_status','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'未发送','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'已发送','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }

        $dict = Dict::create(['name' => '邮件类型','uuid'=>$manager->uuid,'key'=>'email_type','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'同步','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'队列','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }

        $dict = Dict::create(['name' => '队列通道','uuid'=>$manager->uuid,'key'=>'email_queue_priority','module_id'=>$module_id]);
        if($dict->id){
            $data=[];
            $data[] =['dict_id' => $dict->id,'name'=>'普通','value'=>'0'];
            $data[] =['dict_id' => $dict->id,'name'=>'vip','value'=>'1'];
            DB::table('admin_dict_value')->insert($data);
        }
        return 'install_ok';
    }

    public function uninstall($module_id){
        parent::uninstall($module_id);
        return 'uninstall_ok';
    }


}
