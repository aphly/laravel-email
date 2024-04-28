<?php

namespace Aphly\LaravelEmail\Jobs;

use Aphly\LaravelEmail\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //public $tries = 2;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 30;

    private $arr;

    //php artisan queue:work --queue=email_vip,email

    public function __construct($arr)
    {
        $this->arr = $arr;
        if(isset($arr['queue_priority']) && $arr['queue_priority']==1){
            $this->onQueue('email_vip');
        }else{
            $this->onQueue('email');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->arr['email_model']->email && $this->arr['mail_build']){
            $this->arr['emailSite']->config();
            try{
                if($this->arr['emailSite']->cc){
                    Mail::to($this->arr['email_model']->email)->cc($this->arr['emailSite']->cc)->send($this->arr['mail_build']);
                }else{
                    Mail::to($this->arr['email_model']->email)->send($this->arr['mail_build']);
                }
                Email::where('id',$this->arr['email_model']->id)->update(['res'=>'success','status'=>1]);
            }catch (\Exception $e) {
                Email::where('id',$this->arr['email_model']->id)->update(['res'=>$e->getMessage()]);
            }
        }
    }


}
