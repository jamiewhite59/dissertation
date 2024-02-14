<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventItem;

class EventItemController extends Controller {
    public function create(Request $request) {
        $eventItem = new EventItem;

        $eventItem->item_id = $request->item_id;
        $eventItem->event_id = $request->event_id;
        $eventItem->status = 'allocated';
        $eventItem->save();
    }
}
