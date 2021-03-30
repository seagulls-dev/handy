<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Support;
use Illuminate\Http\Request;
use Rennokki\Plans\Models\PlanModel;
use Rinvex\Subscriptions\Models\Plan;
use Validator;


class PlanController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(PlanModel::with('features')->paginate(config('constants.paginate_per_page')));
    }

    public function show($id)
    {
        $planModel = PlanModel::find($id);

        return response()->json($planModel->load('features'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'leads_per_day' => 'required|numeric'
        ]);

        $planModel = PlanModel::find($id);
        $planModel->fill($request->only(['name', 'description', 'price']));
        $planModel->save();

        // update feature limit
        $featureLeadsPerDay = $planModel->features->where('code', 'leads_per_day')->first();
        $featureLeadsPerDay->limit = $request->leads_per_day;
        $featureLeadsPerDay->save();

        return response()->json($planModel->load('features'));
    }
}
