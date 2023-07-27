<?php

namespace Aphly\LaravelEmail\Models;

use Aphly\Laravel\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailSite extends Model
{
    use HasFactory;
    protected $table = 'email_site';
    //public $timestamps = false;
    protected $fillable = [
        'appid','host','secret','status'
    ];

    function findOne(){

    }
}
