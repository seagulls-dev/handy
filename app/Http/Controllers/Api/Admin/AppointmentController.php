<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\AppointmentExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = Appointment::whereRaw("1=1");

        if($request->user_like) {
            $user_email = $request->user_like;
            $appointments = $appointments->whereHas('user', function ($query) use ($user_email){
                $query->where('email', 'like', "%$user_email%");
            });
        }

        if($request->provider_like) {
            $provider_email = $request->provider_like;
            $appointments = $appointments->whereHas('provider', function ($query) use ($provider_email){
                $query->where('email', 'like', "%$provider_email%");
            });
        }

        if($request->date_like) {
            $appointments = $appointments->whereDate('date', $request->date_like);
        }

        if($request->status_like) {
            $appointments = $appointments->where('status', $request->status_like);
        }

        return response()->json($appointments->orderBy('date', 'desc')->paginate(config('constants.paginate_per_page')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);

        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        $request->validate([
            'date' => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i',
            'status' => 'required|in:pending,accepted,ongoing,complete,cancelled,rejected'
        ]);

        $appointment->fill($request->all());

        $appointment->save();

        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $appointment->delete();

        return response()->json([], 204);
    }

    public function export()
    {
        return Excel::download(new AppointmentExport(), 'appointments.xlsx');
    }
}
