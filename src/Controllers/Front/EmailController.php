<?php

namespace Aphly\LaravelEmail\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\LaravelEmail\Mail\Send;
use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        if($request->isMethod('post')) {
            list($input,$emailSite) = $this->_check($request);
            $input['site_id'] = $emailSite->id;
            $email_model = Email::create($input);
            if($email_model->id){
                $input['email_model'] = $email_model;
                $input['mail_build'] = new Send($email_model);
                $input['emailSite'] = $emailSite;
                $input['queue_priority'] = ($input['queue_priority']??0)?1:0;
                $email_model->send($input);
                throw new ApiException(['code'=>0,'msg'=>'success']);
            }else{
                throw new ApiException(['code'=>5,'msg'=>'error']);
            }
        }
    }

    public function _check($request)
    {
        Email::clearOverDays();
        $input = $request->all();
        Verifier::handle($input,[
            'email'=>'required',
            'app_id'=>'required',
            'sign'=>'required',
            'timestamp'=>'required',
            'title'=>'required',
            'content'=>'required',
        ],[
            'email.required'=>'email缺少',
            'app_id.required'=>'App_id缺少',
            'sign.required'=>'签名缺少',
        ]);
        $emailSite = EmailSite::where('app_id',$input['app_id'])->statusOrError();
        if($this->sign($input,$emailSite->app_key)!=$input['sign']){
            throw new ApiException(['code'=>4,'msg'=>'sign error']);
        }
        return [$input,$emailSite];
    }

    function sign($input,$app_key){
        return md5(md5($input['app_id'].$input['email'].$app_key).$input['timestamp']);
    }
}
