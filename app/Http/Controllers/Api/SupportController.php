<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SupportRequest;
use App\Models\Support;

class SupportController extends Controller
{
    /**
     * Create the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function store(SupportRequest $request)
    {
        $support = Support::create($request->all());
        $support->save();
        return response()->json($support);
    }
}
