<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    use HasFactory;

    public $timestamps = false; 
    protected $fillable = ['token','user_id','expires_at'];




    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
    public function otp(){
        return $this-> hasOne(Otp::class,"token");
    }
}
