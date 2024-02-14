<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemFormRequest;
use App\Http\Requests\PieceFormRequest;
use App\Models\Item;
use App\Models\Piece;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $items = Item::all();

        return Inertia::render('Items/Index', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Items/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemFormRequest $request) {
        $item = new Item;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->image = $request->image;
        $item->stock_type = $request->stock_type;
        $item->save();

        return redirect('/items');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id): Response {
        $item = Item::find($id);
        $pieces = $item->pieces;

        return Inertia::render('Items/Create', [
            'item' => $item,
            'pieces' => $pieces,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemFormRequest $request, $id): RedirectResponse {
        $item = Item::find($id);

        $item->title = $request->title;
        $item->description = $request->description;
        $item->image = $request->image;
        $item->stock_type = $request->stock_type;
        $item->save();

        return redirect('/items');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): RedirectResponse {
        Item::destroy($id);

        return redirect('/items');
    }

    public function createPiece(PieceFormRequest $request): RedirectResponse {
        $piece = new Piece;

        $piece->item_id = $request->item_id;
        $piece->code = $request->code;
        $piece->save();

        return redirect()->route('items.edit', $request->item_id);
    }

    public function destroyPiece(Request $request, $id): RedirectResponse {
        Piece::destroy($id);

        return back();
    }

    public function updatePiece(PieceFormRequest $request, $id): RedirectResponse {
        $piece = Piece::find($id);

        $piece->code = $request->code;
        $piece->save();

        return back();
    }
}
