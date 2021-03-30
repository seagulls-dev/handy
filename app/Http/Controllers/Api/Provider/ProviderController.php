<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\AddressCreateRequest;
use App\Http\Requests\Api\Provider\ProviderUpdateRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->provider);
    }

    public function update(ProviderUpdateRequest $request)
    {
        $provider = Auth::user()->provider;
        $provider->fill($request->all());
        $provider->save();

        // detach existing categories
        $provider->subcategories()->detach();

        // attach categories with menu item
        foreach($request->sub_categories as $categoryId) {
            $provider->subcategories()->attach($categoryId);
        }

        return response()->json(Auth::user()->provider);
    }

    public function ratings()
    {
        return response()->json(Auth::user()->provider->ratings()->paginate(config('constants.paginate_per_page')));
    }
}
