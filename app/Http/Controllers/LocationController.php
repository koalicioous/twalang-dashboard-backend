<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Activity;
use App\Purchase;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::all();
        $result = [];

        foreach($locations as $loc ){
            $loc['activity_count'] = $this->countActivity($loc->id);
            $loc['purchase_count'] = $this->countPurchase($loc->id);
            array_push($result,$loc);
        };

        return response()->json(
            $result
        );
    }

    public function countActivity($location_id){
        return Activity::where('location_id',$location_id)->get()->count();
    }

    public function countPurchase($location_id){
        $count = 0;
        $activities = Activity::where('location_id',$location_id)->get();
        foreach($activities as $act){
            $count += Purchase::where('activity_id',$act->id)->get()->count();
        }
        return $count;
    }

    public function countGuest($location_id){
        return Activity::where('location_id',$location_id)->get();
    }

}
