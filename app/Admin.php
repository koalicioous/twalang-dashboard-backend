<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'name', 'email', 'password','birthdate','gender'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
}
