<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\Auth\Registered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckUserRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users via REST API
    |
    */

//    public function authenticate(LoginRequest $request)
//    {
//        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//            $user = Auth::user();
//            $token = $user->createToken('Default')->accessToken;
//            return response()->json(["token" => $token, "user" => $user]);
//        }
//        return response()->json(["error" => "Invalid Login"], 400);
//    }

    public function authenticate(LoginRequest $request)
    {
        $publicKeyURL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';
        $kids = json_decode(file_get_contents($publicKeyURL), true);

        if($request->token) {
            try {
                $decoded = JWT::decode($request->token, $kids, array('RS256'));

                if($decoded->iss !== config('firebase.iss')) {
                    throw new \Exception('Wrong FIREBASE_ISS provided');
                }

                $mobile_number = property_exists($decoded, 'phone_number') ? $decoded->phone_number : null;

                if(empty($mobile_number)) {
                    throw new \Exception('Mobile number not present in token');
                }

                $user = User::where('mobile_number', 'like', '%' . substr($mobile_number, config('constants.mobile_number_length') * -1) .'%' )->first();

                if(!$user) {
                    return response()->json(["message" => 'User does not exist'], 404);
                }

                if(!$user->hasRole($request->role)) {
                    $role = Role::where('name', $request->role)->first();
                    $user->roles()->attach($role);
                    event(new Registered($user, $request->role));
                }

                $token = $user->createToken('Default')->accessToken;
                $user->mobile_verified = 1;
                $user->save();
                return response()->json(["token" => $token, "user" => $user->refresh()]);
            } catch(\Exception $ex) {
                throw new BadRequestHttpException($ex->getMessage());
            }
        }
        throw new BadRequestHttpException('token_not_provided');
    }

    public function checkUser(CheckUserRequest $request)
    {
        return response()->json(["message" => "User exists"]);
    }
}
