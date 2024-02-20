<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventItemRequest;
use Illuminate\Http\Request;
use App\Models\EventItem;
use App\Models\Piece;

class EventItemController extends Controller {
    public function create(Request $request) {
        $eventItem = new EventItem;

        $eventItem->item_id = $request->item_id;
        $eventItem->event_id = $request->event_id;
        $eventItem->status = 'reserved';
        $eventItem->save();
    }

    public function destroy(Request $request) {
        EventItem::whereIn('id', $request->ids)->delete();
    }

    public function addPiece(EventItemRequest $request) {
        $piece = Piece::firstWhere('code', $request->piece_code);

        if ($piece === null) {
            return back()->withErrors(['not_found' => "No piece was found with code $request->piece_code"]);
        }

        $availablePiece = EventItem::where('event_id', $request->event_id)->where('piece_id', $piece->id)->get();
        if (count($availablePiece) > 0) {
            return back()->withErrors(['already_allocated' => 'Piece already allocated to event']);
        }

        $eventItem = EventItem::where('event_id', $request->event_id)->where('piece_id', null)->where('item_id', $piece->item->id)->first();
        if ($eventItem === null) {
            return back()->withErrors(['not_required' => 'No items available for allocation with this piece.']);
        }

        $eventItem->piece_id = $piece->id;
        $eventItem->status = 'allocated';
        $eventItem->save();
    }

    public function removePiece(Request $request) {
        $eventItem = EventItem::find($request->eventItem_id);

        $eventItem->piece_id = null;
        $eventItem->status = 'reserved';
        $eventItem->save();
    }
}
