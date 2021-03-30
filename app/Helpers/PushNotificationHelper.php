<?php
/**
* Created by PhpStorm.
* User: ujjwal
* Date: 12/6/17
* Time: 8:17 PM
*/

namespace App\Helpers;


use LaravelFCM\Facades\FCM;
use App\Models\PostActivity;
use App\Models\CommentActivity;
use Illuminate\Support\Facades\Log;
use Berkayk\OneSignal\OneSignalClient;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PushNotificationHelper
{
    static function send($token, $title, $body, $data)
    {
        try {
            $data['title'] = $title;
            $data['body'] = $body;
            
            Log::info('Push notification - ' . $token, $data);
            
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);
            
            $notificationBuilder = new PayloadNotificationBuilder($title);
            $notificationBuilder->setBody($body)
            ->setSound('default');
            
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($data);
            
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            
            if($token) {
                Log::info('Push notification', ['Sending...', $token, $data]);
                FCM::sendTo($token, $option, null, $data);
            }
        } catch (\Exception $ex) {
            Log::info('Unable to send Push notification', [$token, $data]);
        }
    }
    
    static function getOneSignalInstance($targetUserRole="customer") {
        $appId = "";
        $restApiKey = "";
        if($targetUserRole == "customer") {
            $appId = env('ONESIGNAL_APP_ID_CUSTOMER', ""); 
            $restApiKey = env('ONESIGNAL_REST_API_CUSTOMER', ""); 
        }
        if($targetUserRole == "provider") {
            $appId = env('ONESIGNAL_APP_ID_PROVIDER', ""); 
            $restApiKey = env('ONESIGNAL_REST_API_PROVIDER', ""); 
        }
        return new OneSignalClient($appId, $restApiKey, "");
        
    }
}
