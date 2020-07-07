<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Location;
use App\Activity;
use App\Purchase;

class ProductDesk extends Controller
{
    public function productSummary(){
        return response()->json([
            "activities" => Activity::all()->count(),
            "locations" => Location::all()->count(),
            "categories" => Category::all()->count(),
            "purchases" => Purchase::all()->count()
        ]);
    }

    public function locationPerformanceTable(){

        $locations = Location::all();
        $result = [];

        foreach($locations as $loc){
            $loc['activitiesCount'] = Activity::where('location_id',$loc->id)->get()->count();
            $loc['successfulPurchase'] = $loc->purchases()->where('status','Success')->get()->count();
            $loc['successfulRate'] = round($loc->purchases()->where('status','Success')->get()->count()/$loc->purchases()->get()->count() * 100,2);
            array_push($result,$loc);
        }

        return response()->json(
            $result
        );
    }

    public function successfulPurchaseDoughnut(){

        $locations              = Location::all();
        $location_name          = [];
        $successful_purchase    = Purchase::where('status','Success')->get()->count();
        $successful_data         = [];

        foreach($locations as $loc){
            array_push($location_name,$loc->name);
            array_push($successful_data,round($loc->purchases()->where('status','Success')->get()->count()/$successful_purchase*100,2));
        }

        return response()->json([
            "labels"    => $location_name,
            "datasets"  => [
                [
                "label" => "Persentase pembelian berhasil berdasarkan lokasi",
                "data"  => $successful_data,
                "backgroundColor" => ['#ff5252','#ff9752','#ffab52','#ffc252','#ffd452','#52c8ff','#61d3ed','#6189ed','#dded61','#db2e2e','#24aded','#06c972','#82db2e','#ed9805','#d6ed05','#7505ed',]
                ]
            ]
        ]);
    }

    public function categorySuccessfulDoughnut(){
        $categories              = Category::all();
        $category_name          = [];
        $successful_purchase    = Purchase::where('status','Success')->get()->count();
        $successful_data         = [];

        foreach($categories as $cat){
            array_push($category_name,$cat->name);
            array_push($successful_data,round($cat->purchases()->where('status','Success')->get()->count()/$successful_purchase*100,2));
        }

        return response()->json([
            "labels"    => $category_name,
            "datasets"  => [
                [
                "label" => "Persentase pembelian Berhasil Berdasarkan Kategori",
                "data"  => $successful_data,
                "backgroundColor" => ['#ff5252','#ff9752','#ffab52','#ffc252','#ffd452','#52c8ff','#61d3ed','#6189ed','#dded61','#db2e2e','#24aded','#06c972','#82db2e','#ed9805','#d6ed05','#7505ed',]
                ]
            ]
        ]);
    }

    public function categoryPerformanceTable(){
        $categories = Category::all();
        $result = [];

        foreach($categories as $cat){
            $cat['activitiesCount'] = Activity::where('category_id',$cat->id)->get()->count();
            $cat['successfulPurchase'] = $cat->purchases()->where('status','Success')->get()->count();
            $cat['successfulRate'] = round($cat->purchases()->where('status','Success')->get()->count()/$cat->purchases()->get()->count() * 100,2);
            array_push($result,$cat);
        }

        return response()->json(
            $result
        );
    }

    public function locationPerformance($id){

        $location = Location::find($id);

        if(empty($location)){
            return response()->json([
                "message" => "Resource not found"
            ],400);
        } else {

            $location_purchases = $location->purchases()->where('status','Success')->get();
            $categories = Category::all();
            $categories_name = [];
            $category_count = [];
            $amount = [];

            foreach($categories as $c){
                array_push($categories_name,$c->name);
            }

            foreach($location_purchases as $loc){
                $count = $loc->activity->category->id;
                array_push($category_count,$count);
            }

            $purchase_category_amount = array_count_values($category_count);
            ksort($purchase_category_amount);
            
            for($i=0;$i<count($categories_name);$i++){
                $key = $i+1;
                if(empty($purchase_category_amount["$key"])){
                    $amount[$i] = 0;
                } else {
                    $amount[$i] = $purchase_category_amount["$key"];
                }
            }

            return response()->json([
                "labels"    => $categories_name,
                "datasets"  => [
                    [
                        "label" => "Kategori Terjual",
                        "data"  => $amount,
                        "backgroundColor" => ['#ff5252','#ff9752','#ffab52','#ffc252','#ffd452','#52c8ff','#61d3ed','#6189ed','#dded61','#db2e2e','#24aded','#06c972']
                    ]
                ]
            ]);
        }
    }

    public function categoryPerformance($id){

        $category = Category::find($id);

        if(empty($category)){
            return response()->json([
                "message" => "Resource not found"
            ],400);
        } else {

            $category_purchases = $category->purchases()->where('status','Success')->get();
            $locations = Location::all();
            $locations_name = [];
            $location_count = [];
            $amount = [];

            foreach($locations as $l){
                array_push($locations_name,$l->name);
            }

            foreach($category_purchases as $cat){
                $count = $cat->activity->location->id;
                array_push($location_count,$count);
            }

            $purchase_location_amount = array_count_values($location_count);
            ksort($purchase_location_amount);

            for($i=0;$i<count($locations_name);$i++){
                $key = $i+1;
                if(empty($purchase_location_amount["$key"])){
                    $amount[$i] = 0;
                } else {
                    $amount[$i] = $purchase_location_amount["$key"];
                }
            }

            return response()->json([
                "labels"    => $locations_name,
                "datasets"  => [
                    [
                        "label" => "Kategori Terjual Dalam Kota",
                        "data"  => $amount,
                        "backgroundColor" => ['#ff5252','#ff9752','#ffab52','#ffc252','#ffd452','#52c8ff','#61d3ed','#6189ed','#dded61','#db2e2e','#24aded','#06c972','#ff5252','#ff9752','#ffab52','#ffc252']
                    ]
                ]
            ]);
        }
    }

    public function locationExpenses(){
        $locations = Location::all();
        $result = [];

        foreach($locations as $loc){
            $expenses = 0;
            $successful_expenses = $loc->purchases()->where('status','Success')->get();
            foreach($successful_expenses as $suc){
                $expenses += $suc->gross_total;
            }
            $loc['expenses'] = $expenses;
            array_push($result,$loc);
        }

        return $result;
    }

    public function categoryExpenses(){
        $categories = Category::all();
        $result = [];

        foreach($categories as $loc){
            $expenses = 0;
            $successful_expenses = $loc->purchases()->where('status','Success')->get();
            foreach($successful_expenses as $suc){
                $expenses += $suc->gross_total;
            }
            $loc['expenses'] = $expenses;
            array_push($result,$loc);
        }

        return $result;
    }
}
