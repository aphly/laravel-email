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
            $url = parse_url($request->url());
            if($input['appid'] && $url){
                $emailSite = EmailSite::where('appid',$input['appid'])->where('status',1)->firstOrError();
                if($url['host']==$emailSite->host){
                    if($this->sign($input,$emailSite->secret)==$input['sign']){
                        $input['site_id'] =$emailSite->id;
                        $email_obj = Email::create($input);
                        if($email_obj){
                            (new MailSend($input['type']))->do($email_obj->email,new Send($email_obj),$input['queue_priority'],$email_obj);
                        }
                    }
                }
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }

    function sign($input,$secret){
        return md5(md5($input['appid'].$input['email'].$secret).$input['timestamp']);
    }
}
