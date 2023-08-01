<?php

namespace Aphly\LaravelEmail\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Mail\MailSend;
use Aphly\LaravelEmail\Mail\Send;
use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            //$input = array_map(fn($i)=>trim($i),$input);
            if($input['appid']){
                $emailSite = EmailSite::where('appid',$input['appid'])->where('status',1)->firstOrError();
                if($this->sign($input,$emailSite->secret)==$input['sign']){
                    $input['site_id'] =$emailSite->id;
                    if(empty($input['email']) || empty($input['title']) || empty($input['content'])){
                        throw new ApiException(['code'=>1,'msg'=>'email or title or content error']);
                    }
                    $email_obj = Email::create($input);
                    if($email_obj){
                        (new MailSend($input['type']))->do($email_obj->email,
                            new Send($email_obj),$input['queue_priority'],$email_obj,$emailSite,$input['is_cc']);
                    }else{
                        throw new ApiException(['code'=>5,'msg'=>'error']);
                    }
                }else{
                    throw new ApiException(['code'=>4,'msg'=>'appid error']);
                }
            }else{
                throw new ApiException(['code'=>2,'msg'=>'no appid']);
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }

    function sign($input,$secret){
        return md5(md5($input['appid'].$input['email'].$secret.$input['type'].$input['queue_priority'].$input['is_cc']).$input['timestamp']);
    }
}
