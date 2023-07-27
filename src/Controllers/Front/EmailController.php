<?php

namespace Aphly\LaravelEmail\Controllers\Front;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelEmail\Models\Email;
use Aphly\LaravelEmail\Models\EmailSite;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function add(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            $input['ipv4'] = $request->ip();
            $url = parse_url($input['url']);
            if($input['appid'] && $url){
                $emailHost = EmailSite::where('appid',$input['appid'])->where('status',1)->firstOrError();
                if($url['host']==$emailHost->host){
                    $input['url'] = '/'.basename($input['url']);
                    $input['host_id'] =$emailHost->id;
                    $info = Email::where('host_id',$emailHost->id)->where('ipv4',$input['ipv4'])->where('url',$input['url'])->first();
                    if(!empty($info) && $info->created_at->isToday()){
                        $info->increment('view');
                    }else{
                        $ip_int = ip2long($input['ipv4']);
                        Email::create($input);
                    }
                }
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
