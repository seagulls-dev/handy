<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login admin user
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if(!Auth::user()->hasRole('administrator')) {
                return response()->json(["message" => "Permission denied. No suitable role found"], 400);
            }
            $user = Auth::user();
            $token = $user->createToken('Default')->accessToken;
            return response()->json(["token" => $token, "user" => $user]);
        }
        return response()->json(["message" => "Invalid Login"], 400);
    }
}
