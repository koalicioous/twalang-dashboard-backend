<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Request is not valid",
                "code"    => 902,
            ], 400);
        }

        $admin = Admin::where("email", $request->email)->first();

        if (empty($admin)) {
            return response()->json([
                "message" => "Resource not found",
                "code"    => 803,
            ], 404);
        }

        if (Hash::check($request->password, $admin->password)) {
            $token = Str::random(80);

            $admin->token = $token;
            $admin->save();
            $admin->password = null;

            return response()->json([
                "message" => "Request accepted",
                "code"    => 900,
                "result" => [
                    "token" => $token,
                    "admin" => $admin,
                ]
            ], 200);
        } else {
            return response()->json([
                "message" => "Wrong password",
                "code"    => 911
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        if (Authentication::isValid($request)) {
            $admin = User::where("token", $request->token)->first();
            $admin->token = null;
            $admin->save();

            return response()->json([
                "message" => "Request accepted",
                "code"    => 900,
            ], 200);
        } else {
            return response()->json([
                "message" => "Request rejected",
                "code"    => 901,
            ], 401);
        }
    }
}
