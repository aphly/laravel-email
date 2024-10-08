<?php

namespace Aphly\LaravelEmail\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelEmail\Mail\Send;
use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    public $index_url = '/email_admin/email/index';

    public $p_url = '/email_admin/site/index';

    public $currArr = ['name'=>'邮件','key'=>'email','admin'=>'email_admin'];

    public function index(Request $request)
    {
        $site_id = $request->query('site_id','');
        $res['emailSite'] = EmailSite::where('id',$site_id)->firstOrError();
        $res['search']['email'] = $request->query('email', '');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Email::when($res['search'],
                            function ($query, $search) {
                                if($search['email']!==''){
                                    $query->where('email', $search['email']);
                                }
                            })
                        ->where('site_id', $site_id)
                        ->orderBy('id', 'desc')
                        ->Paginate(config('base.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->host,'href'=>$this->index_url.'?site_id='.$res['emailSite']->id],
        ]);
        return $this->makeView('laravel-email::admin.email.index', ['res' => $res]);
    }

    public function detail(Request $request)
    {
        $site_id = $request->query('site_id','');
        $res['emailSite'] = EmailSite::where('id',$site_id)->firstOrError();
        $res['info'] = Email::where('id',$request->query('id',0))->firstOrNew();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->host,'href'=>$this->index_url.'?site_id='.$res['emailSite']->id],
            ['name'=>'详情','href'=>'/email_admin/'.$this->currArr['key'].'/detail?id='.$res['info']->id.'&site_id='.$res['emailSite']->id]
        ]);
        return $this->makeView('laravel-email::admin.email.detail',['res'=>$res]);
    }

    public function del(Request $request)
    {
        $query = $request->query();
        $redirect = $query?$this->index_url.'?'.http_build_query($query):$this->index_url;
        $post = $request->input('delete');
        if(!empty($post)){
            Email::whereIn('id',$post)->delete();
            throw new ApiException(['code'=>0,'msg'=>'操作成功','data'=>['redirect'=>$redirect]]);
        }
    }

    public function test(Request $request)
    {
        $input = $request->all();
        $res['emailSite'] = EmailSite::where('id',$input['site_id'])->firstOrError();
        if($request->isMethod('post')) {
            //$input['site_id'] = $res['emailSite']->id;
            $input['type'] = $res['emailSite']->type?1:0;
            $input['queue_priority'] = ($input['queue_priority']??0)?1:0;
            $email_model = Email::create($input);
            if($email_model->id){
                $args = [
                    'type'=>$res['emailSite']->type,
                    'queue_priority'=>$input['queue_priority'],
                    'emailSite'=>$res['emailSite'],
                    'email_model'=>$email_model,
                    'mail_build' => new Send($email_model)
                ];
                $email_model->send($args);
                throw new ApiException(['code'=>0,'msg'=>'success']);
            }else{
                throw new ApiException(['code'=>5,'msg'=>'error']);
            }
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'测试','href'=>$this->index_url]
            ]);
            return $this->makeView('laravel-email::admin.email.test',['res'=>$res]);
        }
    }

    public function test_bf(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            $input['timestamp'] = time();
            $input['sign'] = md5(md5($input['app_id'].$input['email'].$input['app_key'].$input['type'].$input['queue_priority'].$input['is_cc']).$input['timestamp']);
            $res = Http::connectTimeout(5)->post(config('base.email_host').'/email/send',$input);
            throw new ApiException(['code'=>1,'msg'=>'发送中','data'=>['html'=>$res->body()]]);
        }else{
            $res['breadcrumb'] = Breadcrumb::render([
                ['name'=>$this->currArr['name'].'测试','href'=>$this->index_url]
            ]);
            return $this->makeView('laravel-email::admin.email.test',['res'=>$res]);
        }
    }
}
