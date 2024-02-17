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

    public function addPiece(EventItemRequest $request) {
        $piece = Piece::firstWhere('code', $request->piece_code);
        $availablePiece = EventItem::where('piece_id', $piece->id)->get();
        if (count($availablePiece) > 0) {
            //TODO: throw/ display error saying piece already allocated to this project
            dd('Piece already allocated to event');
            return;
        }

        $eventItem = EventItem::where('piece_id', null)->where('item_id', $piece->item->id)->firstOr(function() {
            //TODO: throw/display error saying no items require this piece
            dd('No items require this piece');
            return;
        });

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
