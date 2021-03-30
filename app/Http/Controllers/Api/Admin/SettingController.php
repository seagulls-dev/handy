<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brotzka\DotenvEditor\DotenvEditor as Env;
use Brotzka\DotenvEditor\Exceptions\DotEnvException;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(Setting::all());
    }

    public function update(Request $request)
    {
        $inputs = $request->all();

        foreach ($inputs as $key => $value) {
            try {
                $setting = Setting::where('key', $key)->firstOrFail();
                $setting->value = $value;
                $setting->save();
            } catch (ModelNotFoundException $ex) {
                //
            }
        }

        return response()->json([]);
    }

    public function envList(Request $request)
    {
        $env = new Env();
        return response()->json($env->getContent());
    }

    /**
     * Update env variables.
     *
     * @param Request $request
     * @return mixed
     */
    public function updateEnv(Request $request)
    {
        $env = new Env();
        try {
            $env->changeEnv([
                'MAIL_DRIVER'   => $request->MAIL_DRIVER,
                'MAIL_HOST'   => $request->MAIL_HOST,
                'MAIL_PORT'   => $request->MAIL_PORT,
                'MAIL_USERNAME'   => $request->MAIL_USERNAME,
                'MAIL_PASSWORD'   => $request->MAIL_PASSWORD,
                'MAIL_FROM_ADDRESS'   => $request->MAIL_FROM_ADDRESS,
                'MAIL_FROM_NAME'   => $request->MAIL_FROM_NAME,
                'MAILGUN_DOMAIN'   => $request->MAILGUN_DOMAIN,
                'MAILGUN_SECRET'   => $request->MAILGUN_SECRET,
                'FCM_SERVER_KEY'   => $request->FCM_SERVER_KEY,
                'FCM_SENDER_ID'   => $request->FCM_SENDER_ID,
                'ONESIGNAL_APP_ID'   => $request->ONESIGNAL_APP_ID,
                'ONESIGNAL_REST_API'   => $request->ONESIGNAL_REST_API,
                'APP_TIMEZONE'   => $request->APP_TIMEZONE,
                'STRIPE_KEY'   => $request->STRIPE_KEY,
                'STRIPE_SECRET'   => $request->STRIPE_SECRET,
            ]);
        } catch (DotEnvException $e) {
        }

        return response()->json([]);
    }

    public function timezoneList()
    {
        return response()->json(timezone_identifiers_list());
    }
}
