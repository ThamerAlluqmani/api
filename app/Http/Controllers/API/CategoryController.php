<?php

namespace App\Http\Controllers\API;

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


    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function index()
    {
        //
        return auth()->user()->categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = $request->validate([
            'title' => 'required'
        ]);

        if (auth()->user()->categories()->create($request->all())) {
            return ['message' => 'Category created successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //

        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }
        $rules = $request->validate([
            'title' => 'required'
        ]);


        if ($category->update($request->all())) {
            return ['message' => 'Category updated successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //

        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($category->delete()) {
            return ['message' => 'Category has been hidden successfully'];
        }
        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

    public function restore($categoryId)
    {
        //

        $category = Category::withTrashed()->findOrFail($categoryId);

        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($category->restore()) {
            return ['message' => 'Category restored successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }

    public function forceDelete($categoryId)
    {
        //

        $deletedCategory = Category::withTrashed()->findOrFail($categoryId);

        if (auth()->id() != $deletedCategory->user_id) {
            return response()->json(['message' => 'Error , permission denied'], 401);
        }

        if ($deletedCategory->forceDelete()) {
            return ['message' => 'Category Deleted successfully'];
        }

        return response()->json(['message' => 'There is an error , please try again later'], 500);
    }


}
