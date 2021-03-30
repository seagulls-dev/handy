<?php

namespace App\Models;

use App\Http\Requests\Api\Customer\ProviderProfileListRequest;
use App\Models\Auth\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Rennokki\Plans\Models\PlanSubscriptionModel;

class ProviderProfile extends Model
{
    protected $table = 'provider_profiles';

    protected $fillable = ['primary_category_id', 'user_id', 'is_verified', 'document_url', 'image_url', 'price', 'price_type', 'address', 'longitude', 'latitude', 'about'];

    protected $with = array('primary_category','subcategories', 'user');

    protected $appends = array('ratings', 'ratingscount', 'plan');

    public static function search($user, ProviderProfileListRequest $request)
    {
        $distanceDelta = 15000;

        $distanceSetting = Setting::where('key', 'distance_limit')->first();
        if($distanceSetting) {
            $distanceDelta = $distanceSetting->value ? (int)$distanceSetting->value : $distanceDelta;
        }

        // show providers listed with in particular distance
        $subqueryDistance = "ST_Distance_Sphere(Point(provider_profiles.longitude,"
            . " provider_profiles.latitude),"
            . " Point($request->long, $request->lat ))"
            . " as distance";

        // advertisement
        $subqueryAdvertisement = "EXISTS (SELECT id FROM plans_subscriptions WHERE model_id=provider_profiles.user_id AND plan_id IN(1,2)) as advertisement";

        $subqueryDistanceWhere = "ST_Distance_Sphere(Point(provider_profiles.longitude,"
            . " provider_profiles.latitude),"
            . " Point($request->long, $request->lat ))"
            . " < " . $distanceDelta;

        $providers = ProviderProfile::select('*', DB::raw($subqueryAdvertisement), DB::raw($subqueryDistance))->whereRaw($subqueryDistanceWhere);

        // filter for category
        $categoryId = $request->input('category');
        $providers = $providers->whereHas('subcategories', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        });

        $providers->orderBy('advertisement', 'desc')->get();

        return $providers;
    }

    /**
     * Calculate rating of a store
     * @return integer
     */
    public function getRatingsAttribute()
    {
        return Rating::where('provider_id', $this->attributes['id'])->get()->avg->rating;
    }

    /**
     * Calculate rating count
     * @return integer
     */
    public function getRatingscountAttribute()
    {
        return Rating::where('provider_id', $this->attributes['id'])->count();
    }

    public function getPlanAttribute()
    {
        $user = User::find($this->attributes['user_id']);
        if($user->hasActiveSubscription()) {
            return $user->activeSubscription()->plan_id;
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User\User', 'user_id');
    }

    public function primary_category()
    {
        return $this->belongsTo('App\Models\Category', 'primary_category_id');
    }

    public function subcategories()
    {
        return $this->belongsToMany('App\Models\Category', 'providers_categories', 'provider_id');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating', 'provider_id');
    }

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment', 'provider_id');
    }

    public function portfolios()
    {
        return $this->hasMany('App\Models\ProviderPortfolio', 'provider_id');
    }
}
