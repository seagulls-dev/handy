<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\AddressCreateRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->addresses);
    }

    public function store(AddressCreateRequest $request)
    {
        $address = new Address();
        $address->fill($request->all());
        $address->user_id = Auth::user()->id;
        $address->save();

        return response()->json($address);
    }

    public function update(AddressCreateRequest $request, Address $address)
    {
        $address->fill($request->all());
        $address->save();

        return response()->json($address);
    }

    public function show(Address $address)
    {
        return response()->json($address);
    }

    public function delete(Address $address)
    {
        $address->delete();
        return response()->json([], 204);
    }
}
