<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\User;
use Illuminate\Http\Request;

class MarketingDesk extends Controller
{
    public function briefSummary(){
        return response()->json([
            "userCount" => User::all()->count(),
            "purchaseCount" => Purchase::all()->count(),
            "conversionRate" => round(User::all()->count()/Purchase::all()->count(),4) * 100
        ]);
    }
}
