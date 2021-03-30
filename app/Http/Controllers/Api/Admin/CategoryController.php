<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if($request->title_like) {
            $categories = Category::where('title', 'like', "%" . $request->title_like . "%");
        }

        return response()->json($categories->orderBy('title', 'desc')->paginate(config('constants.paginate_per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'image|nullable',
	    'secondary_image' => 'image|nullable',
            'parent_id' => 'exists:categories,id|nullable'
        ]);

        $category = new Category();
        $category->fill($request->all());
        if($request->image) {
            $path = $request->file('image')->store('uploads');
            $category->image_url = Storage::url($path);
        }

	if($request->secondary_image) {
            $path = $request->file('secondary_image')->store('uploads');
            $category->secondary_image_url = Storage::url($path);
        }

        $category->save();

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'image|nullable',
            'parent_id' => 'exists:categories,id|nullable'
        ]);

        $category = Category::find($id);

        $category->fill($request->all());
        if($request->image) {
            $path = $request->file('image')->store('uploads');
            $category->image_url = Storage::url($path);
	}

	if($request->secondary_image) {
            $path = $request->file('secondary_image')->store('uploads');
            $category->secondary_image_url = Storage::url($path);
        }

        $category->save();

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json([], 204);
    }

    public function allPrimaryCategories(Request $request)
    {
        $categories = Category::whereNull('parent_id');
        return response()->json($categories->get());
    }

    public function allSubCategories(Request $request, Category $category)
    {
        return response()->json($category->subcategories);
    }
}
