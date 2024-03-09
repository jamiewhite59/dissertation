<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::all();

        $categories = $categories->map(function($category) {
            $category->items = $category->items;
            return $category;
        });

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(CategoryFormRequest $request) {
        $category = new Category;
        $category->title = $request->title;
        $category->description = $request->description;

        $category->save();

        return redirect('/categories');
    }

    public function edit(Request $request, $id) {
        $category = Category::find($id);
        $category->items = $category->items;

        return Inertia::render('Categories/Category', [
            'category' => $category,
        ]);
    }

    public function update(CategoryFormRequest $request, $id) {
        $category = Category::find($id);

        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();

        return redirect('/categories');
    }

    public function destroy(Request $request) {
        Category::destroy($request->id);

        return redirect('/categories');
    }
}
