<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['title','price','description','host_id','category_id','location_id'];

    public function host(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function purchases(){
        return $this->hasMany('App\Purchase');
    }
}
