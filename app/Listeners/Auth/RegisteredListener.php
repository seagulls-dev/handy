<?php

namespace App\Listeners\Auth;

use App\Events\Auth\PostRegistration;
use App\Events\Auth\Registered;
use App\Mail\WelcomeUser;
use App\Models\Auth\User\User;
use App\Models\DeliveryProfile;
use App\Models\ProviderProfile;
use App\Models\Store;
use App\Notifications\Admin\NewUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        try {
            if ($event->role == 'provider') {
                ProviderProfile::create([
                    'user_id' => $event->user->id
                ]);
            }
        } catch (\Exception $ex) {
            Log::error('Exception occurred', [$ex->getMessage(), $ex->getTraceAsString()]);
        }
    }
}
