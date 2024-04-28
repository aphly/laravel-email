<?php

namespace Aphly\LaravelEmail\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class EmailSite extends Model
{
    use HasFactory;
    protected $table = 'email_site';
    //public $timestamps = false;
    protected $fillable = [
        'host','app_id','app_key','status','type',
        'smtp_host','smtp_port','smtp_encryption','smtp_username','smtp_password','smtp_from_address','smtp_from_name','cc'
    ];

    function config(){
        Config::set('mail.mailers.smtp.host',$this->smtp_host);
        Config::set('mail.mailers.smtp.port',$this->smtp_port);
        Config::set('mail.mailers.smtp.encryption',$this->smtp_encryption);
        Config::set('mail.mailers.smtp.username',$this->smtp_username);
        Config::set('mail.mailers.smtp.password',$this->smtp_password);
        Config::set('mail.from.address',$this->smtp_from_address);
        Config::set('mail.from.name',$this->smtp_from_name);
    }

}
