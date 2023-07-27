<?php

namespace Aphly\LaravelEmail\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelEmail\Mail\Send;
use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
                    $input['site_id'] =$emailSite->id;
                    $email_obj = Email::create($input);
                    if($email_obj){
                        Mail::to($email_obj->email)->send(new Send($email_obj));
                    }
                }
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
