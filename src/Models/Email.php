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
        'site_id','email','title','content','type','queue_priority','status'
    ];

    function handle(){
        $this->status=1;
        return $this->save();
    }

}
