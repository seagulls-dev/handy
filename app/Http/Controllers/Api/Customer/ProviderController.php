<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\RatingCreateRequest;
use App\Http\Requests\Api\Customer\ProviderProfileListRequest;
use App\Models\Auth\User\User;
use App\Models\ProviderProfile;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index(ProviderProfileListRequest $request)
    {
        return response()->json(ProviderProfile::search(Auth::user(), $request)->paginate(config('constants.paginate_per_page')));
    }

    public function show(ProviderProfile $provider)
    {
        return response()->json($provider);
    }

    public function ratings(ProviderProfile $provider)
    {
        return response()->json($provider->ratings()->orderBy('created_at', 'desc')->paginate(config('constants.paginate_per_page')));
    }

    public function portfolios(ProviderProfile $provider)
    {
        return response()->json($provider->portfolios()->orderBy('created_at', 'desc')->get());
    }

    public function rate(ProviderProfile $provider, RatingCreateRequest $request)
    {
        $rating = new Rating();
        $rating->fill($request->all());
        $rating->provider_id = $provider->id;
        $rating->user_id = Auth::user()->id;
        $rating->save();
        return response()->json($rating);
    }

    public function ratingSummary(ProviderProfile $provider)
    {
        return response()->json([
            "average_rating" => $provider->ratings()->avg('rating'),
            "total_ratings" => $provider->ratings()->count(),
            "summary" => DB::table('ratings')->selectRaw('count(*) as total, ROUND(rating) as rounded_rating')
                ->where('provider_id', $provider->id)
                ->groupBy('rounded_rating')
                ->get(),
            "total_completed" => $provider->appointments()->where('status', 'complete')->count()
        ]);
    }

    public function providerByUserId(User $user)
    {
        return response()->json($user->provider);
    }
}
