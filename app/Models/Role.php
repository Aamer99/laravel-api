<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory; 
    public $timestamps = false; 

    // public function users(){
    //     return $this->hasMany(User::class,"type");
    // }
    public function unapprovedUsers(){
        return $this->hasMany(UnapprovedUser::class,"type");
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
