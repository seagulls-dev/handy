<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PlanUsageLog extends Model
{
    protected $table = 'plan_usage_logs';

    protected $fillable = ['subscription_id'];

    public static function getTodayUsage($subscriptionId) {
        return PlanUsageLog::where('subscription_id', $subscriptionId)->whereDate('created_at', Carbon::today())->count();
    }
}
