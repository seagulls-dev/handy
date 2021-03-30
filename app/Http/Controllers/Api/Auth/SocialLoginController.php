<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\Auth\SocialLogin;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\SocialAccount;
use App\Models\Auth\User\User;
use Firebase\JWT\JWT;
use Google_Client;
use Illuminate\Foundation\Auth\RedirectsUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SocialLoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'platform' => 'required|in:google,facebook',
	    'token' => 'required',
	    'os' => 'sometimes|in:android,os'
        ]);

        try {
            $email = null;

            if($request->platform == 'google') {
                $email = $this->_googleLogin($request->token, $request->get('os', 'android'));
            }

            if($request->platform == 'facebook') {
                $email = $this->_facebookLogin($request->token);
            }

            if($email == null) {
                return response()->json(["message" => 'Email not found from token'], 400);
            }

            $user = User::where('email', $email)->first();

            if(!$user) {
                return response()->json(["message" => 'User does not exist'], 404);
            }

            $token = $user->createToken('Default')->accessToken;
            return response()->json(["token" => $token, "user" => $user]);
        } catch(\Exception $ex) {
            throw new BadRequestHttpException($ex->getMessage());
        }
    }

    private function _googleLogin($token, $os) {
	$client_id_env_key = $os == 'android' ? 'GOOGLE_CLIENT_ID' : 'GOOGLE_IOS_CLIENT_ID';
        $client_id = env($client_id_env_key, null);

        if($client_id == null) {
            throw new \Exception('Google Client ID not configured on server');
        }

        $client = new Google_Client(['client_id' => $client_id]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($token);
        if ($payload) {
            return $payload['email'];
        }
        throw new \Exception('Invalid Google Token');
    }

    private function _facebookLogin($token) {
        // https://github.com/facebook/php-graph-sdk
        $app_id = env('FACEBOOK_APP_ID', null);
        $app_secret = env('FACEBOOK_APP_SECRET', null);

        $fb = new \Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.10'
        ]);

        try {
            $response = $fb->get('/me?fields=name,email', $token);
            $me = $response->getGraphUser();
            return $me->getEmail();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Graph returned an error: ' . $e->getMessage());
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }
    }
}
