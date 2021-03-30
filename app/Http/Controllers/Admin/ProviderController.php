<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProviderRequest;
use App\Http\Requests\Admin\ProviderUpdateRequest;
use App\Models\Category;
use App\Models\Provider;
use App\Models\ProviderProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Validator;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.providers.index', ['providers' => ProviderProfile::paginate()]);
    }

    /**
     * Display the specified resource.
     *
     * @param ProviderProfile $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ProviderProfile $provider)
    {
        return view('admin.providers.show', ['provider' => $provider]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProviderProfile $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(ProviderProfile $provider)
    {
        return view('admin.providers.edit', ['provider' => $provider,
            'categories' => Category::whereNull('parent_id')->get(),
            'subcategories' => Category::where('parent_id', $provider->primary_category_id)->get(),
            'selected_subcategories' => $provider->subcategories->pluck(['id'])->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProviderUpdateRequest $request
     * @param ProviderProfile $provider
     * @return mixed
     */
    public function update(ProviderUpdateRequest $request, ProviderProfile $provider)
    {
        $provider->fill($request->all());

        if($request->image_url) {
            $path = $request->file('image_url')->store('uploads');
            $provider->image_url = Storage::url($path);
        }

        $provider->save();

        return redirect()->intended(route('admin.providers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProviderProfile $provider
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ProviderProfile $provider)
    {
        $provider->delete();
        return redirect()->intended(route('admin.providers'));
    }
}
