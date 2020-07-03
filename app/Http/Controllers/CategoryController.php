<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Activity;
use App\Purchase;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        $result = [];

        foreach($categories as $cat){
            $cat['activities'] = $this->countActivity($cat->id);
            $cat['purchases'] = [
                "count"     => $this->countPurchase($cat->id),
                "pending"   => $this->countPendingPurchase($cat->id),
                "success"   => $this->countSuccessPurchase($cat->id),
                "expired"   => $this->countExpiredPurchase($cat->id),
                "finished" => $this->countFinishedPurchase($cat->id)
            ];
            array_push($result,$cat);
        }

        return response()->json(
            $result
        );
    }

    public function countActivity($category_id){
        $activities = Activity::where('category_id',$category_id)->get()->count();
        return [
            "count" => $activities
        ];
    }

    public function countPurchase($category_id){
        $activities = Activity::where('category_id',$category_id)->get();
        $count = 0;

        foreach($activities as $act){
            $count   += Purchase::where('activity_id', $act->id)->get()->count();
        }

        return $count;
    }

    public function countPendingPurchase($category_id){
        $activities = Activity::where('category_id',$category_id)->get();
        $pending = 0;

        foreach($activities as $act){
            $pending   += Purchase::where('activity_id', $act->id)->where('status','Pending')->get()->count();
        }

        return $pending;
    }

    public function countSuccessPurchase($category_id){
        $activities = Activity::where('category_id',$category_id)->get();
        $success = 0;

        foreach($activities as $act){
            $success   += Purchase::where('activity_id', $act->id)->where('status','Success')->get()->count();
        }

        return $success;
    }

    public function countExpiredPurchase($category_id){
        $activities = Activity::where('category_id',$category_id)->get();
        $expired = 0;

        foreach($activities as $act){
            $expired   += Purchase::where('activity_id', $act->id)->where('status','Expired')->get()->count();
        }

        return $expired;
    }

    public function countFinishedPurchase($category_id){
        $activities = Activity::where('category_id',$category_id)->get();
        $finished = 0;

        foreach($activities as $act){
            $finished   += Purchase::where('activity_id', $act->id)->where('status','Finished')->get()->count();
        }

        return $finished;
    }

}
