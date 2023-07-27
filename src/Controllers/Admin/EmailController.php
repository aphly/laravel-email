<?php

namespace Aphly\LaravelEmail\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Breadcrumb;

use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public $index_url = '/email_admin/email/index';
    public $p_url = '/email_admin/Site/index';

    private $currArr = ['name'=>'列表','key'=>'email'];

    public function index(Request $request)
    {
        $Site_id = $request->query('site_id','');
        $res['emailSite'] = EmailSite::where('id',$Site_id)->firstOrError();
        $res['search']['ip'] = $request->query('ip', '');
        $res['search']['string'] = http_build_query($request->query());
        $res['list'] = Email::when($res['search'],
                            function ($query, $search) {
                                if($search['ip']!==''){
                                    $query->where('ipv4', $search['ip']);
                                }
                            })
                        ->where('Site_id', $Site_id)
                        ->orderBy('id', 'desc')
                        ->Paginate(config('admin.perPage'))->withQueryString();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->Site,'href'=>$this->index_url.'?Site_id='.$res['emailSite']->id],
        ]);
        return $this->makeView('laravel-email::admin.email.index', ['res' => $res]);
    }

    public function detail(Request $request)
    {
        $Site_id = $request->query('Site_id','');
        $res['emailSite'] = EmailSite::where('id',$Site_id)->firstOrError();
        $res['info'] = Email::where('id',$request->query('id',0))->firstOrNew();
        $res['breadcrumb'] = Breadcrumb::render([
            ['name'=>$this->currArr['name'].'管理','href'=>$this->p_url],
            ['name'=>$res['emailSite']->Site,'href'=>$this->index_url.'?Site_id='.$res['emailSite']->id],
            ['name'=>'详情','href'=>'/email_admin/'.$this->currArr['key'].'/detail?id='.$res['info']->id.'&Site_id='.$res['emailSite']->id]
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


}
