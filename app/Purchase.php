<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['activity_id','buyer_id','gross_total','payment_method','status','guest'];

    public function activity(){
        return $this->belongsTo('App\Activity');
    }

    public function buyer(){
        return $this->belongsTo('App\User');
    }
}
