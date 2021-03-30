<?php

namespace App\Http\Controllers\Api\Customer;

use App\Events\NewAppointment;
use App\Events\UpdateAppointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\AppointmentCreateRequest;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->appointments()->orderBy('date', 'desc')->paginate(config('constants.paginate_per_page')));
    }

    public function store(AppointmentCreateRequest $request)
    {
        $appointment = new Appointment();
        $appointment->fill($request->all());
        $appointment->user_id = Auth::user()->id;
        $appointment->status = 'pending';
        $appointment->save();

        event(new NewAppointment($appointment));

        return response()->json(Appointment::find($appointment->id));
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->status = 'cancelled';
        $appointment->save();

        event(new UpdateAppointment($appointment, false));

        return response()->json($appointment);
    }
}
