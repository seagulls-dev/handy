<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use Illuminate\Http\Request;

Route::namespace('Api')->name('api.')->group(function () {

    // Backend Api
    Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

        // auth related
        Route::namespace('Auth')->group(function () {
            Route::post('/login', 'LoginController@authenticate');
        });

        // export data
        Route::get('/download/user', 'UserController@export');
        Route::get('/download/appointment', 'AppointmentController@export');
        Route::get('/download/provider', 'ProviderProfileController@export');

        Route::middleware('auth:api')->group(function () {

            // category
            Route::get('/categories/primary', 'CategoryController@allPrimaryCategories');
            Route::get('/categories/{category}/subcategories', 'CategoryController@allSubCategories');
            Route::apiResource('categories', 'CategoryController');

            // provider profile
            Route::apiResource('providers', 'ProviderProfileController')->except('create');

            // appointment
            Route::apiResource('appointments', 'AppointmentController')->except('create');

            // user
            Route::get('/users/roles', 'UserController@roles');
            Route::apiResource('users', 'UserController');

            // plan
            Route::apiResource('plans', 'PlanController')->except('create')->except('delete');

            // support
            Route::get('/supports', 'SupportController@index');

            // settings
            Route::get('/settings', 'SettingController@index');
            Route::post('/settings', 'SettingController@update');
            Route::get('/settings/env', 'SettingController@envList');
            Route::post('/settings/env', 'SettingController@updateEnv');

            // faq
            Route::apiResource('faqs', 'FaqController');

            // dashboard
            Route::get('/dashboard/appointment-analytics', 'DashboardController@appointmentAnalytics');
            Route::get('/dashboard/user-analytics', 'DashboardController@userAnalytics');
            Route::get('/dashboard/category-summary', 'DashboardController@categorySummary');
            Route::get('/dashboard/daily-active-analytics', 'DashboardController@dailyUserAnalytics');
        });
    });

    Route::namespace('Auth')->group(function () {
        Route::post('/check-user', 'LoginController@checkUser')->name('checkUser');
        Route::post('/login', 'LoginController@authenticate')->name('login');
        Route::post('/register', 'RegisterController@register')->name('register');
        Route::post('/verify-mobile', 'RegisterController@verifyMobile')->name('verifyMobile');
        Route::post('/forgot-password', 'RegisterController@sendResetLinkEmail')->name('forgotPassword');

        Route::post('social/login', 'SocialLoginController@authenticate')->name('social.authenticate');
    });

    Route::post('/support', 'SupportController@store')->name('support.store');

    // system wide settings
    Route::get('/settings', 'SettingController@index')->name('setting.index');

    Route::get('/faq-help', 'FaqController@index')->name('faq.index');

    Route::get('/category', 'CategoryController@index')->name('category.index');

    Route::namespace('Customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/users/provider/{user}', 'ProviderController@providerByUserId')->name('provider.providerByUserId');
        Route::get('/providers', 'ProviderController@index')->name('provider.index');
        Route::get('/providers/{provider}', 'ProviderController@show')->name('provider.show');
        Route::get('/providers/{provider}/rating-summary', 'ProviderController@ratingSummary')->name('provider.ratingsSummary');
        Route::get('/providers/{provider}/ratings', 'ProviderController@ratings')->name('provider.ratings');
        Route::get('/providers/{provider}/portfolios', 'ProviderController@portfolios')->name('provider.portfolio');
    });


    Route::middleware('auth:api')->group(function () {
        // activity log
        Route::post('/activity-log', 'ActiveLogController@store')->name('activitylog.store');

        Route::get('/user', 'UserController@show')->name('user.show');
        Route::put('/user', 'UserController@update')->name('user.update');
        Route::post('/user/push-notification', 'UserController@pushNotification')->name('user.pushNotification');

        /* Customer related APIs */
        Route::namespace('Customer')->prefix('customer')->name('customer.')->group(function () {

            /* provider related */
            Route::post('/providers/{provider}/ratings', 'ProviderController@rate')->name('provider.rate');

            /* address related */
            Route::get('/address', 'AddressController@index')->name('address.index');
            Route::post('/address', 'AddressController@store')->name('address.store');
            Route::get('/address/{address}', 'AddressController@show')->name('address.show');
            Route::put('/address/{address}/update', 'AddressController@update')->name('address.update');
            Route::delete('/address/{address}', 'AddressController@delete')->name('address.delete');

            /* appointment related */
            Route::get('/appointment', 'AppointmentController@index')->name('appointment.index');
            Route::post('/appointment', 'AppointmentController@store')->name('appointment.store');
            Route::post('/appointment/{appointment}/cancel', 'AppointmentController@cancel')->name('appointment.cancel');
        });

        /* Provider related APIs */
        Route::namespace('Provider')->prefix('provider')->name('provider.')->group(function () {

            /* profile related */
            Route::get('/profile', 'ProviderController@index')->name('profile.index');
            Route::put('/profile', 'ProviderController@update')->name('profile.update');
            // ratings
            Route::get('/ratings', 'ProviderController@ratings')->name('ratings.index');

            // portfolio
            Route::get('/portfolio', 'PortfolioController@index')->name('portfolio.index');
            Route::post('/portfolio', 'PortfolioController@store')->name('portfolio.store');
            Route::delete('/portfolio/{portfolio}', 'PortfolioController@delete')->name('portfolio.delete');

            /* appointment related */
            Route::get('/appointment', 'AppointmentController@index')->name('appointment.index');
            Route::put('/appointment/{appointment}', 'AppointmentController@update')->name('appointment.update');

            /* plans related */
            Route::get('/plans', 'PlanController@plans')->name('plans.index');
            Route::post('/plans/{plan}/payment/stripe', 'PlanController@makeStripePayment')->name('plans.makeStripePayment');
            Route::get('/plan-details', 'PlanController@planDetails')->name('plans.planDetails');
        });
    });
});
