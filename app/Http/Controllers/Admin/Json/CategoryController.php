<?php

namespace App\Http\Controllers\Admin\Json;

use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Validator;

class CategoryController extends Controller
{
    public function subcategories(Request $request)
    {
        $category = Category::find($request->category_id);
        return response()->json($category->subcategories);
    }
}
