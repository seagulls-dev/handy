<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Auth routes
 */
Route::group(['namespace' => 'Auth'], function () {

//    // Authentication Routes...
//    Route::get('login', 'LoginController@showLoginForm')->name('login');
//    Route::post('login', 'LoginController@login');
//    Route::get('logout', 'LoginController@logout')->name('logout');
//
//    // Registration Routes...
//    if (config('auth.users.registration')) {
//        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
//        Route::post('register', 'RegisterController@register');
//    }

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

//    // Confirmation Routes...
//    if (config('auth.users.confirm_email')) {
//        Route::get('confirm/{user_by_code}', 'ConfirmController@confirm')->name('confirm');
//        Route::get('confirm/resend/{user_by_email}', 'ConfirmController@sendEmail')->name('confirm.send');
//    }
//
//    // Social Authentication Routes...
//    Route::get('social/redirect/{provider}', 'SocialLoginController@redirect')->name('social.redirect');
//    Route::get('social/login/{provider}', 'SocialLoginController@login')->name('social.login');
});

/**
 * Backend routes
 */
//Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
//
//    // Dashboard
//    Route::get('/', 'DashboardController@index')->name('dashboard');
//
//    //Users
//    Route::get('users', 'UserController@index')->name('users');
//    Route::get('users/{user}', 'UserController@show')->name('users.show');
//    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
//    Route::put('users/{user}', 'UserController@update')->name('users.update');
//    Route::delete('users/{user}', 'UserController@destroy')->name('users.destroy');
//    Route::get('dashboard/log-chart', 'DashboardController@getLogChartData')->name('dashboard.log.chart');
//    Route::get('dashboard/registration-chart', 'DashboardController@getRegistrationChartData')->name('dashboard.registration.chart');
//
//    Route::get('categories', 'CategoryController@index')->name('categories');
//    Route::get('categories/create', 'CategoryController@create')->name('categories.create');
//    Route::post('categories', 'CategoryController@store')->name('categories.store');
//    Route::get('categories/{category}/edit', 'CategoryController@edit')->name('categories.edit');
//    Route::put('categories/{category}', 'CategoryController@update')->name('categories.update');
//    Route::get('categories/{category}/delete', 'CategoryController@destroy')->name('categories.destroy');
//
//    Route::get('providers', 'ProviderController@index')->name('providers');
//    Route::get('providers/create', 'ProviderController@create')->name('providers.create');
//    Route::post('providers', 'ProviderController@store')->name('providers.store');
//    Route::get('providers/{provider}/edit', 'ProviderController@edit')->name('providers.edit');
//    Route::put('providers/{provider}', 'ProviderController@update')->name('providers.update');
//    Route::get('providers/{provider}/delete', 'ProviderController@destroy')->name('providers.destroy');
//
//    Route::get('settings/{setting}/edit', 'SettingController@edit')->name('settings.edit');
//    Route::get('settings', 'SettingController@settings')->name('settings');
//    Route::put('settings/update-env', 'SettingController@updateEnv')->name('settings.updateEnv');
//    Route::put('settings/update-setting', 'SettingController@updateSetting')->name('settings.updateSetting');
//
//    Route::get('ratings', 'RatingController@index')->name('ratings');
//    Route::get('ratings/{id}', 'RatingController@destroy')->name('ratings.destroy');
//
//    Route::group(['namespace' => 'Json', 'as' => 'json.'], function () {
//        Route::get('subcategories', 'CategoryController@subcategories')->name('subcategories');
//    });
//});


Route::get('/', 'HomeController@index');