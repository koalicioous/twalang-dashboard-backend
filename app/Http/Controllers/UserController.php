<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DateTime;

class UserController extends Controller
{
    public function count(){
        return response()->json(
            User::all()->count(),
        );
    }

    public function genderDemographic(){

        $count              = User::all()->count();
        $male               = User::where('gender',1)->get()->count();
        $female             = User::where('gender',0)->get()->count();
        $malePercentage     = round($male/$count,4) * 100;
        $femalePercentage   = round($female/$count,4) * 100;

        return response()->json([
            'allUsers' => $count,
            'male' => [
                'count' => $male,
                'percentage' => $malePercentage
            ],
            'female' => [
                'count' => $female,
                'percentage' => $femalePercentage
            ]
        ]);
    }

    public function ageDemographic(){
        $child = 0; $adolescent = 0; $youngAdult = 0; $middleAged = 0; $aged = 0;
        $childMale = 0; $adolescentMale = 0; $youngAdultMale = 0; $middleAgedMale = 0; $agedMale = 0;
        $users = User::all();

        foreach($users as $user){
            $age = $this->countUserAge($user->birthdate);
            $gender = $this->checkGender($user->gender);

            switch($age){
                case $age > 0 && $age < 12: 
                    $child++;
                    $childMale += $gender;
                    break;
                case $age >11 && $age < 19:
                    $adolescent++;
                    $adolescentMale += $gender;
                    break; 
                case $age > 18 && $age < 25;
                    $youngAdult++;
                    $youngAdultMale += $gender;
                    break;
                case $age > 24 && $age < 35: 
                    $middleAged++;
                    $middleAgedMale += $gender;
                    break;
                case $age > 34: 
                    $aged++;
                    $agedMale += $gender;
                    break;
            }
        }

        $total = $child + $adolescent + $youngAdult + $middleAged + $aged;

        return response()->json([
            "child" => [
                "range" => '0 - 11',
                "count" => $child,
                "percentage" => round($child/$total,2),
                "gender" => [
                    "male" => [
                        "count" => $childMale,
                        "percentage" => round($childMale/$child,2)
                    ],
                    "female" => [
                        "count" => $child - $childMale,
                        "percentage" => round(($child - $childMale)/$child,2)
                    ]
                ]
            ],
            "adolescent" => [
                "range" => '12 - 18',
                "count" => $adolescent,
                "percentage" => round($adolescent/$total,2),
                "gender" => [
                    "male" => [
                        "count" => $adolescentMale,
                        "percentage" => round($adolescentMale/$adolescent,2)
                    ],
                    "female" => [
                        "count" => $adolescent - $adolescentMale,
                        "percentage" => round(($adolescent - $adolescentMale)/$adolescent,2)
                    ]
                ]
            ],
            "youngAdult" => [
                "range" => '19 - 24',
                "count" => $youngAdult,
                "percentage" => round($youngAdult/$total,2),
                "gender" => [
                    "male" => [
                        "count" => $youngAdultMale,
                        "percentage" => round($youngAdultMale/$youngAdult,2)
                    ],
                    "female" => [
                        "count" => $youngAdult - $youngAdultMale,
                        "percentage" => round(($youngAdult - $youngAdultMale)/$youngAdult,2)
                    ]
                ]
            ],
            "middleAged" => [
                "range" => '25 - 35',
                "count" => $middleAged,
                "percentage" => round($middleAged/$total,2),
                "gender" => [
                    "male" => [
                        "count" => $middleAgedMale,
                        "percentage" => round($middleAgedMale/$middleAged,2)
                    ],
                    "female" => [
                        "count" => $middleAged - $middleAgedMale,
                        "percentage" => round(($middleAged - $middleAgedMale)/$middleAged,2)
                    ]
                ]
            ],
            "aged" => [
                "range" => '35++',
                "count" => $aged,
                "percentage" => round($aged/$total,2),
                "gender" => [
                    "male" => [
                        "count" => $agedMale,
                        "percentage" => round($agedMale/$aged,2)
                    ],
                    "female" => [
                        "count" => $aged - $agedMale,
                        "percentage" => round(($aged - $agedMale)/$aged,2)
                    ]
                ]
            ]
        ]);

    }

    public function countUserAge($birthdate){
        return floor((time() - strtotime($birthdate)) / (365*60*60*24));
    }

    public function checkGender($gender){
        if ($gender === 0 ){
            return 0;
        } else {
            return 1;
        }
    }
}
