<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Support;
use Illuminate\Http\Request;
use Validator;


class SupportController  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $supports = Support::whereRaw("1=1");

        return response()->json($supports->orderBy('created_at', 'desc')->paginate(config('constants.paginate_per_page')));
    }
}
