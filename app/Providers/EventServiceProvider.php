<?php

namespace App\Providers;

use App\Events\NewAppointment;
use App\Events\Auth\SocialLogin;
use App\Listeners\Auth\LoginListener;
use App\Listeners\Auth\LogoutListener;
use App\Listeners\Auth\NewAppointmentListener;
use App\Listeners\Auth\UpdateAppointmentListener;
use App\Listeners\Auth\SocialLoginListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Events\Auth\Registered;
use App\Events\UpdateAppointment;
use App\Listeners\Auth\RegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class => [LoginListener::class],
        Logout::class => [LogoutListener::class],
        Registered::class => [RegisteredListener::class],
        SocialLogin::class => [SocialLoginListener::class],
	NewAppointment::class => [NewAppointmentListener::class],
	UpdateAppointment::class => [UpdateAppointmentListener::class]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
