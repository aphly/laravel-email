<?php

namespace Aphly\LaravelEmail\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailSiteController extends Controller
{
    public $index_url='/email_admin/site/index';

    private $currArr = ['name'=>'站点','key'=>'site'];

    public function index(Request $request)
    {
        $res['search']['name'] = $request->query('name','');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = EmailSite::when($res['search'],
            function($query,$search) {
                if($search['name']!==''){
                    $query->where('name', 'like', '%'.$search['name'].'%');
                }
            })
            ->orderBy('id','desc')
            ->Paginate(config('base.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url]
        ]);
        return $this->makeView('laravel-email::admin.site.index',['res'=>$res]);
    }

    public function form(Request $request)
    {
        $res['info'] = EmailSite::where('id',$request->query('id',0))->firstOrNew();
        if($res['info']->id){
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'编辑','href'=>'/email_admin/'.$this->currArr['key'].'/form?id='.$res['info']->id]
            ]);
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'管理','href'=>$this->index_url],
                ['name'=>'新增','href'=>'/email_admin/'.$this->currArr['key'].'/form']
            ]);
        }
        return $this->makeView('laravel-email::admin.site.form',['res'=>$res]);
    }

    public function save(Request $request){
        $input = $request->all();
        Verifier::handle($input,[
            'host'=>'required',
            'status'=>'required',
            'type'=>'required','smtp_host'=>'required',
            'smtp_port'=>'required','smtp_encryption'=>'required',
            'smtp_username'=>'required','smtp_password'=>'required',
            'smtp_from_address'=>'required','smtp_from_name'=>'required',
        ]);
        if(empty($input['app_id'])){
            $input = array_map(fn($i)=>trim($i),$input);
            $input['app_id'] = date('Ymd') . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $input['app_key'] = Str::random(32);
        }
        EmailSite::updateOrCreate(['id'=>$request->query('id',0)],$input);
        throw new ApiException(['code'=>0,'msg'=>'success','data'=>['redirect'=>$this->index_url]]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            EmailSite::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

}
