<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::whereRaw("1=1");

        // search
        if ($request->search) {
            $categories = $categories->where('title', 'like', "%" . $request->search . "%");
        } else if ($request->category_id) {
            $categories = $categories->where('parent_id', $request->category_id);
        } else {
            $categories = $categories->whereNull('parent_id');
        }

        $categories = $categories->orderBy('title', 'desc')->paginate(50);
        return response()->json($categories);
    }
}
