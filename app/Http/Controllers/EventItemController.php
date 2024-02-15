<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventItem;

class EventItemController extends Controller {
    public function create(Request $request) {
        $eventItem = new EventItem;

        $eventItem->item_id = $request->item_id;
        $eventItem->event_id = $request->event_id;
        $eventItem->status = 'reserved';
        $eventItem->save();
    }

    public function addPiece(Request $request) {
        $eventItem = EventItem::find($request->eventItem_id);

        $eventItem->piece_id = $request->piece_id;
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
