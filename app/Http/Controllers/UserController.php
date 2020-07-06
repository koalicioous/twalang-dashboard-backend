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
            "labels" => ['Male','Female'],
            "datasets" => [
                [
                    "label" => "Gender Comparison",
                    "data" => [$male,$female],
                    "backgroundColor" => ["#0297fa","#04cc83"]
                ]
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
            "labels" => ['child (0 - 11)','Adolescent (12 - 18)','Young Adult (19 - 24)','Middle Aged (25 - 25)','Aged (35++)'],
            "datasets" => [
                [
                    "label" => 'Total',
                    "backgroundColor" => '#f87979',
                    "data" => [$child, $adolescent, $youngAdult, $middleAged, $aged]
                ],
                [
                    "label" => 'Male',
                    "backgroundColor" => '#ffbf1f',
                    "data" => [$childMale, $adolescentMale, $youngAdultMale, $middleAgedMale, $agedMale]
                ],
                [
                    "label" => 'Female',
                    "backgroundColor" => '#ff1f80',
                    "data" => [ $child - $childMale, $adolescent - $adolescentMale, $youngAdult - $youngAdultMale,
                    $middleAged - $middleAgedMale, $aged - $agedMale]
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
