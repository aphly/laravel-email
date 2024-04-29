<?php

namespace Aphly\LaravelEmail\Models;

use Aphly\Laravel\Models\Model;
use Aphly\LaravelEmail\Jobs\EmailJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Email extends Model
{
    use HasFactory;
    protected $table = 'email';
    //public $timestamps = false;
    protected $fillable = [
        'site_id','email','title','content','type','queue_priority','status','is_cc','res'
    ];

    static public function clearOverDays(int $days=30){
        $clear = Cache::get('clearEmailOverDays');
        if(!$clear){
            $seconds = 3600*24*$days;
            Cache::put('clearEmailOverDays',1,$seconds);
            self::where('created_at','<',time()-$seconds)->delete();
        }
    }

    public function send($args){
        if($args['emailSite']->type){
            EmailJob::dispatch($args);
        }else{
            EmailJob::dispatchSync($args);
        }
    }



}
