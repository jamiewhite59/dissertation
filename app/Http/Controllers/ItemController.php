<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemFormRequest;
use App\Http\Requests\PieceFormRequest;
use App\Models\Item;
use App\Models\Piece;
use App\Models\EventItem;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Carbon\Carbon;

use Illuminate\Database\QueryException;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $items = Item::all();
        $categories = Category::all();

        $items = $items->map(function($item) {
            $item->quantity = count($item->pieces);
            $item->category = $item->category;
            return $item;
        });

        return Inertia::render('Items/Index', [
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Items/Item');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemFormRequest $request) {
        $item = new Item;
        $item->title = $request->title;
        $item->description = $request->description ?: '';
        $item->image = $request->image;
        $item->stock_type = $request->stock_type;
        $item->category_id = $request->category_id;
        $item->save();
        if ($request->stock_type === 'bulk') {
            for($x = 0; $x < $request->quantity; $x++) {
                $piece = new Piece;
                $piece->item_id = $item->id;
                $piece->save();
            }
        }

        return redirect("/items/$item->id/edit");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id): Response {
        $item = Item::find($id);
        $item->pieces = $item->pieces;
        $item->pieces = $item->pieces->map(function($piece) {
            $piece->group = $piece->group;
        });
        $item->events = $item->events();
        $categories = Category::all();

        return Inertia::render('Items/Item', [
            'item' => $item,
            'categories' => $categories,
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
        $item->category_id = $request->category_id;

        //TODO: if stock type is changed whilst there are pieces attached to the item then do not allow saving
        $item->stock_type = $request->stock_type;
        $item->save();

        if ($request->stock_type === 'bulk') {
            $currentPieces = Piece::where('item_id', $item->id)->orderBy('id', 'desc')->get();
            $numCurrentPieces = count($currentPieces);
            $difference = abs($numCurrentPieces - $request->quantity);
            if ($numCurrentPieces < $request->quantity) {
                for($x=0; $x < $difference; $x++) {
                    $piece = new Piece;
                    $piece->item_id = $item->id;
                    $piece->save();
                }
            } else if ($numCurrentPieces > $request->quantity) {
                $eventsWithitem = EventItem::where('item_id', $item->id)->where('status', '!=', 'completed')->get()->unique('event_id');
                $eventsWithoutEndDate = [];
                $eventsWithEndDate = [];
                foreach($eventsWithitem as $eventItem) {
                    $event = $eventItem->event;
                    if ($event->end_date) {
                        array_push($eventsWithEndDate, $eventItem->event);
                    } else {
                        array_push($eventsWithoutEndDate, $eventItem->event);
                    }
                }

                $ongoingItemQuantity = 0;
                foreach($eventsWithoutEndDate as $event) {
                    $eventItems = EventItem::where('item_id', $item->id)->where('event_id', $event->id)->get();
                    $ongoingItemQuantity += count($eventItems);
                }

                $finiteItemQuantity = 0;
                foreach($eventsWithEndDate as $event) {
                    $otherEvents = $eventsWithEndDate;
                    $index = array_search($event, $eventsWithEndDate);
                    array_splice($otherEvents, $index, 1);

                    $eventItemQuantity = count(EventItem::where('event_id', $event->id)->where('item_id', $item->id)->get());
                    foreach($otherEvents as $otherEvent) {
                        $startA = Carbon::parse($event->start_date);
                        $endA = Carbon::parse($event->end_date);
                        $startB = Carbon::parse($otherEvent->start_date);
                        $endB = Carbon::parse($otherEvent->end_date);

                        if (($startB <= $endA) && ($endB >= $startA)) {
                            $otherEventItemQuantity = count(EventItem::where('event_id', $otherEvent->id)->where('item_id', $item->id)->get());
                            $eventItemQuantity += $otherEventItemQuantity;
                        }
                    }
                    if ($finiteItemQuantity < $eventItemQuantity) {
                        $finiteItemQuantity = $eventItemQuantity;
                    }
                }

                $requiredPieces = $ongoingItemQuantity + $finiteItemQuantity;
                $deletablePieces = $numCurrentPieces - $request->quantity;
                if ($requiredPieces > $request->quantity) {
                    return back()->withErrors(['unable' => "Cannot delete this many pieces, at least $requiredPieces pieces are required"]);
                } else {
                    for($x=0; $x < $deletablePieces; $x++) {
                        $currentPieces[$x]->delete();
                    }
                }
            }
        } else {
            $pieces = Piece::where('item_id', $item->id)->where('code', null)->get();
            foreach($pieces as $piece) {
                $foundUniqueCode = false;
                while ($foundUniqueCode === false) {
                    $randCode = rand(1, 10000);
                    $existingCode = Piece::where('code', $randCode)->get();
                    if (count($existingCode) === 0) {
                        $foundUniqueCode = true;
                        $piece->code = $randCode;
                        $piece->save();
                    }
                }
            }
        }
        return redirect('/items');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request) {
        if ($request->stock_type === 'hire') {
            $itemPieces = Piece::where('item_id', $request->id)->get();
            for($x = 0; $x < count($itemPieces); $x++) {
                $eventItems = EventItem::where('piece_id', $itemPieces[$x]->id)->where('status', '!=', 'completed')->get();
                if (count($eventItems) !== 0) {
                    return back()->withErrors(['unable' => 'At least one piece is in use in an event']);
                }
                Piece::destroy($itemPieces[$x]->id);
            }
        } else {
            $activeEventItemsForItem = EventItem::where('item_id', $request->id)->where('status', '!=', 'completed')->get();
            if (count($activeEventItemsForItem) > 0) {
                return back()->withErrors(['unable' => 'At least one piece is in use in an event']);
            }
        }
        try {
            Item::destroy($request->id);
        } catch (QueryException $exception) {
            return back()->withErrors(['sql_error' => 'Cannot remove item']);
        }
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
        $eventItems = EventItem::where('piece_id', $id)->where('status', '!=', 'completed')->get();
        if (count($eventItems) !== 0) {
            return back()->withErrors(['unable' => 'An event item is currently using this piece']);
        }

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
