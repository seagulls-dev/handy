<?php

namespace App\Models;

use App\Http\Requests\Api\Customer\ProviderProfileListRequest;
use App\Models\Auth\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Rennokki\Plans\Models\PlanSubscriptionModel;

class ProviderPortfolio extends Model
{
    protected $table = 'provider_portfolios';

    protected $fillable = ['provider_id', 'image_url', 'link'];

    public function provider()
    {
        return $this->belongsTo('App\Models\ProviderProfile', 'provider_id');
    }
}
