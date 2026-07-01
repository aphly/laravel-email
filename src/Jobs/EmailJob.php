<?php

namespace Aphly\LaravelEmail\Jobs;

use Aphly\LaravelEmail\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 30;
    //public $retryAfter= 35;

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
            $smtpConfig = [
                'transport'  => 'smtp',
                'host'       => $this->arr['emailSite']->smtp_host,
                'port'       => $this->arr['emailSite']->smtp_port,
                'encryption' => $this->arr['emailSite']->smtp_encryption,
                'username'   => $this->arr['emailSite']->smtp_username,
                'password'   => $this->arr['emailSite']->smtp_password,
                'timeout'    => null,
                'local_domain' => '',
            ];
            config([
                'mail.from.address' => $this->arr['emailSite']->smtp_from_address,
                'mail.from.name'    => $this->arr['emailSite']->smtp_from_name,
                'mail.mailers.dynamic_smtp' => $smtpConfig
            ]);
            try{
                if($this->arr['emailSite']->cc){
                    Mail::mailer('dynamic_smtp')->to($this->arr['email_model']->email)->cc($this->arr['emailSite']->cc)->send($this->arr['mail_build']);
                }else{
                    if($this->arr['email_model']->cc){
                        Mail::mailer('dynamic_smtp')->to($this->arr['email_model']->email)->cc($this->arr['email_model']->cc)->send($this->arr['mail_build']);
                    }else{
                        Mail::mailer('dynamic_smtp')->to($this->arr['email_model']->email)->send($this->arr['mail_build']);
                    }
                }
                Email::where('id',$this->arr['email_model']->id)->update(['res'=>'success','status'=>1]);
                app('mail.manager')->forgetMailers();
            }catch (\Exception $e) {
                Email::where('id',$this->arr['email_model']->id)->update(['res'=>$e->getMessage(),'status'=>2]);
            }
        }
    }


}
