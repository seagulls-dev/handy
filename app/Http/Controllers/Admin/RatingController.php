<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ratings = Rating::whereRaw('1=1');

        return view('admin.ratings.index', ['ratings' => $ratings->sortable(['created_at' => 'asc'])->paginate()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rating $rating
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->intended(route('admin.ratings'));
    }
}
