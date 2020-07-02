<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name'];

    public function activities(){
        return $this->hasMany('App\Activity');
    }
}
