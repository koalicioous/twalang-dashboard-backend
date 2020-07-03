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
            $loc['guest'] = [
                "satisfied" => $this->countSatisfiedGuest($loc->id),
                "prospected" => $this->countProspectedGuest($loc->id)
            ];
            $loc['purchases'] = [
                "count" => $this->countPurchase($loc->id),
                "pending" => $this->countPurchasePending(($loc->id)),
                "expired" => $this->countPurchaseExpired($loc->id),
                "success" => $this->countPurchaseSuccess($loc->id),
                "finished" => $this->countPurchaseFinished($loc->id)
            ];
            $loc['revenue'] = [
                "gainedRevenue" => $this->countGainRevenue($loc->id),
                "prospectedRevenue" => $this->countProspectedRevenue($loc->id)
            ];
            $loc['conversion_rate'] = $this->countConversionRate($loc->id);
            array_push($result,$loc);
        };

        return response()->json(
            $result
        );
    }

    public function location($location_id){
        $location = Location::find($location_id);
        $result = [];

        $result['activity_count'] = $this->countActivity($location_id);
        $result['guest'] = [
            "satisfied" => $this->countSatisfiedGuest($location_id),
            "prospected" => $this->countProspectedGuest($location_id)
        ];
        $result['purchases'] = [
            "count" => $this->countPurchase($location_id),
            "pending" => $this->countPurchasePending($location_id),
            "expired" => $this->countPurchaseExpired($location_id),
            "success" => $this->countPurchaseSuccess($location_id),
            "finished" => $this->countPurchaseFinished($location_id)
        ];
        $result['revenue'] = [
            "gainedRevenue" => $this->countGainRevenue($location_id),
            "prospectedRevenue" => $this->countProspectedRevenue($location_id)
        ];
        $result['conversion_rate'] = $this->countConversionRate($location_id);

        return $result;

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

    public function countPurchasePending($location_id){
        $count = 0;
        $activities = Activity::where('location_id',$location_id)->get();
        foreach($activities as $act){
            $count += Purchase::where([
                ['activity_id',$act->id],
                ['status',"Pending"]
            ])->get()->count();
        }
        return $count;
    }

    public function countPurchaseSuccess($location_id){
        $count = 0;
        $activities = Activity::where('location_id',$location_id)->get();
        foreach($activities as $act){
            $count += Purchase::where([
                ['activity_id',$act->id],
                ['status',"Success"]
            ])->get()->count();
        }
        return $count;
    }

    public function countPurchaseExpired($location_id){
        $count = 0;
        $activities = Activity::where('location_id',$location_id)->get();
        foreach($activities as $act){
            $count += Purchase::where([
                ['activity_id',$act->id],
                ['status',"Expired"]
            ])->get()->count();
        }
        return $count;
    }

    public function countPurchaseFinished($location_id){
        $count = 0;
        $activities = Activity::where('location_id',$location_id)->get();
        foreach($activities as $act){
            $count += Purchase::where([
                ['activity_id',$act->id],
                ['status',"Finished"]
            ])->get()->count();
        }
        return $count;
    }

    public function countSatisfiedGuest($location_id){
        $count = 0;

        $activities = Activity::where([
            ['location_id',$location_id]
        ])->get();

        foreach($activities as $act){
            $purchases = Purchase::where([
                ['activity_id',$act->id],
                ['status',"Finished"]
            ])->get();

            foreach($purchases as $purchase){
                $count += $purchase->guest;
            }
        }

        return $count;
    }

    public function countProspectedGuest($location_id){
        $count = 0;

        $activities = Activity::where([
            ['location_id',$location_id]
        ])->get();

        foreach($activities as $act){
            $purchases = Purchase::where([
                ['activity_id',$act->id],
            ])->get();

            foreach($purchases as $purchase){
                $count += $purchase->guest;
            }
        }

        return $count;
    }

    public function countGainRevenue($location_id){
        $count = 0;

        $activities = Activity::where([
            ['location_id',$location_id]
        ])->get();

        foreach($activities as $act){
            $purchases = Purchase::where([
                ['activity_id',$act->id],
                ['status',"Finished"]
            ])->get();

            foreach($purchases as $purchase){
                $count += $purchase->gross_total;
            }
        }

        return $count;
    }

    public function countProspectedRevenue($location_id){
        $count = 0;

        $activities = Activity::where([
            ['location_id',$location_id]
        ])->get();

        foreach($activities as $act){
            $purchases = Purchase::where([
                ['activity_id',$act->id],
            ])->get();

            foreach($purchases as $purchase){
                $count += $purchase->gross_total;
            }
        }

        return $count;
    }

    public function countConversionRate($location_id){
        $success = 0;
        $total = 0;
        $conversion_rate = 0;

        $activities = Activity::where([
            ['location_id',$location_id]
        ])->get();

        foreach($activities as $act){
            $total = Purchase::where([
                ['activity_id',$act->id] 
            ])->get()->count();
        }

        foreach($activities as $act){
            $success = Purchase::where([
                ['activity_id',$act->id] 
            ])->where(function ($query) {
                $query->where('status','Success')
                ->orWhere('status','Finished');
            })->get()->count();
        }

        return round($conversion_rate = $success/$total,2);
    }

}
