<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiStripePaymentRequest;
use App\Models\Address;
use App\Models\Setting;
use Carbon\Carbon;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Auth;
use Rennokki\Plans\Models\PlanModel;
use Rennokki\Plans\Models\PlanSubscriptionModel;
use Rennokki\Plans\Models\PlanSubscriptionUsageModel;


class PlanController extends Controller
{
    public function plans()
    {
        return response()->json(PlanModel::with('features')->get());
    }

    public function planDetails()
    {
        $user = Auth::user();
        $leadsRemainingForToday = null;

        if($user->hasActiveSubscription()) {
            $subscription = $user->activeSubscription();
            $leadsUsedToday = PlanSubscriptionUsageModel::where('subscription_id', $subscription->id)
                ->whereDate('created_at', Carbon::today())->count();
            $limit = $subscription->features()->code('leads_per_day')->first()->limit/30;
            $leadsRemainingForToday = $limit - $leadsUsedToday;
        }

        return response([
            "subscription" => $user->activeSubscription(),
            "active" => $user->hasActiveSubscription(),
            "leads_remaining_for_today" => $leadsRemainingForToday
        ]);
    }

    public function makeStripePayment(PlanModel $plan, ApiStripePaymentRequest $request)
    {
        if(env('DEMO_SKIP_PAYMENT')) {
            $this->onPaymentSuccess($plan);
            return response()->json(["status" => true]);
        }

        $amount = number_format((float)$plan->price, 2, '.', '');
        $currency = Setting::where('key', 'currency')->first()->value;

        try {
            $token = $request->token;
            $charge = Stripe::charges()->create([
                'amount' => $amount,
                'currency' => strtolower($currency),
                'description' => 'Payment for Plan ' . $plan->name,
                'source' => $token,
            ]);

            $this->onPaymentSuccess($plan);

            return response()->json(["status" => true, 'charge' => $charge]);
        } catch(\Exception $ex) {
            abort(400);
        }
    }

    private function onPaymentSuccess(PlanModel $plan)
    {
        $user = Auth::user();

        if($user->hasActiveSubscription()) {
            $user->cancelCurrentSubscription();
        }
        $user->subscribeTo($plan, $plan->duration);
    }
}
