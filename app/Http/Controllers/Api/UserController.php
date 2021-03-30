<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Language;
use Illuminate\Http\Request;
use App\Models\Auth\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PushNotificationHelper;

class UserController extends Controller
{
    /**
    * Display the store of current logged in user
    *
    * @return \Illuminate\Http\Response
    */
    public function show()
    {
        return response()->json(Auth::user());
    }
    
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if($request->fcm_registration_id) {
            $user->fcm_registration_id = $request->fcm_registration_id;
        }
        
        if($request->fcm_registration_id_provider) {
            $user->fcm_registration_id_provider = $request->fcm_registration_id_provider;
        }
        
        if($request->image_url) {
            $user->image_url = $request->image_url;
        }
        
        if($request->language) {
            $user->language = $request->language;
        }
        
        $user->save();
        
        return response()->json($user);
    }
    
    public function pushNotification(Request $request)
    {
        $request->validate([
            "role" => "required|in:customer,provider",
            "user_id" => "required|exists:users,id"
        ]);
        
        $notifyingUser = Auth::user();
        $notifiedUser = User::find($request->user_id);

        $playerId = $request->role == "customer" ? $notifiedUser->fcm_registration_id : $notifiedUser->fcm_registration_id_provider;

        $languageCode = $notifiedUser->language;
        $language = new Language($languageCode);
        
        $oneSignal = PushNotificationHelper::getOneSignalInstance($request->role);
        $oneSignal->sendNotificationToUser(
            $language->get('chat_new_message'),
            $playerId,
            null,
            ["title" => $language->get('chat_new_message'), "body" => $language->get('chat_new_body') . $notifyingUser->name]
        );

        return response()->json([], 200);
    }
}
