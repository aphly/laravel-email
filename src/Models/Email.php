<?php

namespace Aphly\LaravelEmail\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;
    protected $table = 'email';
    //public $timestamps = false;
    protected $fillable = [
        'ipv4','url','referrer','keyword','language','platform','userAgent','webdriver','ipv6','view','host_id','country_iso'
    ];

}
