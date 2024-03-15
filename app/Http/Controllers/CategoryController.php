<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Item;

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
        $category->items = $category->items->map(function($item) {
            $item->pieces = $item->pieces;
            $item->events = $item->events();
            return $item;
        });
        $items = Item::all();
        $items = $items->map(function($item) {
            $item->category = $item->category;
            return $item;
        });

        return Inertia::render('Categories/Category', [
            'category' => $category,
            'items' => $items,
        ]);
    }

    public function update(CategoryFormRequest $request, $id) {
        $category = Category::find($id);

        $category->title = $request->title;
        $category->description = $request->description;
        $category->save();

        return redirect('/categories');
    }

    public function destroy(Request $request, $id) {
        Category::destroy($id);

        return redirect('/categories');
    }

    public function addItem(Request $request, $id) {
        $item = Item::find($request->id);

        $item->category_id = $id;
        $item->save();
        return back();
    }

    public function removeItem(Request $request, $id) {
        $item = Item::find($request->id);

        $item->category_id = null;
        $item->save();
        return back();
    }
}
