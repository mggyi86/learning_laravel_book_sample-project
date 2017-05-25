<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('category.index');
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories|string|max:30',
        ]);

        $category = Category::create(['name' => $request->name]);
        $category->save();
        return Redirect::route('category.index');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:40|unique:categories,name,' .$id
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return Redirect::route('category.show', [ 'category' => $category ]);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return Redirect::route('category.index');
    }
}
