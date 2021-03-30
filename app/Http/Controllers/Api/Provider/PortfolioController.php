<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\PortfolioCreateRequest;
use App\Models\ProviderPortfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    public function index()
    {
        $providerId = Auth::user()->provider->id;
        return response()->json(ProviderPortfolio::where('provider_id', $providerId)->orderBy('created_at', 'desc')->get());
    }

    public function store(PortfolioCreateRequest $request)
    {
        $providerId = Auth::user()->provider->id;

        $portfolio = new ProviderPortfolio();
        $portfolio->fill($request->all());
        $portfolio->provider_id = $providerId;
        $portfolio->save();

        return response()->json($portfolio);
    }

    public function delete(ProviderPortfolio $portfolio)
    {
        $portfolio->delete();
        return response()->json([], 204);
    }
}
