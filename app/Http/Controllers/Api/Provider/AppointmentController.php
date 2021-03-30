<?php

namespace App\Http\Controllers\Api\Provider;

use App\Events\UpdateAppointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\AppointmentUpdateRequest;
use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\PlanUsageLog;
use Illuminate\Support\Facades\Auth;
use Rennokki\Plans\Models\PlanSubscriptionUsageModel;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->provider->appointments()->orderBy('date', 'desc')->paginate(config('constants.paginate_per_page')));
    }

    public function update(Appointment $appointment, AppointmentUpdateRequest $request)
    {
        if($request->status == 'accepted') {
            // can accept the job?
            $canAccept = false;
            $user = Auth::user();
            $leadsRemainingForToday = null;

            if ($user->hasActiveSubscription()) {
                $subscription = $user->activeSubscription();
                $leadsUsedToday = PlanUsageLog::getTodayUsage($subscription->id);                
                $limit = $subscription->features()->code('leads_per_day')->first()->limit / 30;
                $leadsRemainingForToday = $limit - $leadsUsedToday;

                if ($leadsRemainingForToday > 0) {
                    $canAccept = true;
                }
            }

            if(!$canAccept) {
                return response()->json(["message" => 'Not enough credits left'], 403);
            }
        }

        $old_status = $appointment->status;
        $rescheduled = false;

        if($request->date && $appointment->date != $request->date) {
            // if provider has changed the date of appointment, we consider it as reschedule
            $rescheduled = true;
        }

        $appointment->fill($request->all());
        $appointment->save();

        if($old_status != $appointment->status && $appointment->status != 'rejected') {
            AppointmentStatusLog::create([
                'user_id' => $appointment->user_id,
                'appointment_id' => $appointment->id,
                'status' => $appointment->status
            ]);
        }

        if($old_status != $appointment->status && $appointment->status == 'accepted') {
            // deduct the credits
            $subscription = Auth::user()->activeSubscription();
            $subscription->consumeFeature('leads_per_day', 1);
            PlanUsageLog::create(["subscription_id"=> $subscription->id]);
        }

        event(new UpdateAppointment($appointment, $rescheduled));

        return response()->json($appointment->refresh());
    }
}
