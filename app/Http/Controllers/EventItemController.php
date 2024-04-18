<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\EventItem;
use App\Models\Piece;
use App\Models\Group;

class EventItemController extends Controller {
    public function create(Request $request) {
        $itemPieces = Piece::where('item_id', $request->item_id)->get();
        $numItemPieces = count($itemPieces);
        $sameItems = EventItem::where('item_id', $request->item_id)->where('event_id', $request->event_id)->get();
        if ((count($sameItems) + $request->quantity) > $numItemPieces) {
            return back()->withErrors(['unable' => "Cannot add $request->quantity more of this item to this event (max $numItemPieces)"]);
        }

        for($x=0; $x < $request->quantity; $x++) {
            $eventItem = new EventItem;

            $eventItem->item_id = $request->item_id;
            $eventItem->event_id = $request->event_id;
            $eventItem->status = 'reserved';
            $eventItem->save();
        }
    }

    public function destroy(Request $request) {
        EventItem::whereIn('id', $request->ids)->delete();
    }

    public function addPiece(EventItemRequest $request) {
        $piece = Piece::firstWhere('code', $request->piece_code);

        if ($piece === null) {
            return back()->withErrors(['not_found' => "No piece was found with code $request->piece_code"]);
        }

        if ($piece->group_id) {
            return back()->withErrors(['unable' => 'This piece is part of a group & cannot be added separately']);
        }

        $availablePiece = EventItem::where('event_id', $request->event_id)->where('piece_id', $piece->id)->get();
        if (count($availablePiece) > 0) {
            return back()->withErrors(['already_allocated' => 'Piece already allocated to event']);
        }
        if ($request->event_item_id) {
            $eventItem = EventItem::find($request->event_item_id);
        } else {
            $eventItem = EventItem::where('event_id', $request->event_id)->where('piece_id', null)->where('item_id', $piece->item->id)->first();
        }
        if ($eventItem === null || $eventItem->piece_id !== null) {
            return back()->withErrors(['not_required' => 'No items available for allocation with this piece.']);
        }

        $eventItem->piece_id = $piece->id;
        $eventItem->status = 'allocated';
        $eventItem->save();
        return back()->with('success', 'Item allocated: ' . $request->piece_code);
    }

    public function actionBulk(Request $request) {
        $oldEventitems = EventItem::where('event_id', $request->event_id)->where('item_id', $request->item_id)->where('status', $request->old_status)->get();
        $numOldItems = count($oldEventitems);
        if ($numOldItems === 0) {
            return back()->withErrors(['not_found' => "No items found to be $request->new_status"]);
        }
        if ($numOldItems <= $request->quantity) {
            $oldEventitems->toQuery()->update([
                'status' => $request->new_status,
            ]);
        } else {
            for ($index=0; $index < $request->quantity; $index++) {
                $oldEventitems[$index]->status = $request->new_status;
                $oldEventitems[$index]->save();
            }
        }
        return back()->with('success', "Bulk items $request->new_status");
    }

    public function allocateBulkItem(Request $request) {
        $unallocatedEventitems = EventItem::where('event_id', $request->event_id)->where('item_id', $request->item_id)->where('status', 'reserved')->get();
        $numUnallocatedItems = count($unallocatedEventitems);
        if ($numUnallocatedItems === 0) {
            return back()->withErrors(['not_found' => 'No items found to be allocated']);
        }
        if ($numUnallocatedItems <= $request->quantity) {
            $unallocatedEventitems->toQuery()->update([
                'status' => 'allocated',
            ]);
        } else {
            for ($index=0; $index < $request->quantity; $index++) {
                $unallocatedEventitems[$index]->status = 'allocated';
                $unallocatedEventitems[$index]->save();
            }
        }
        return back()->with('success', 'Bulk items allocated');
    }

    public function checkoutPiece(Request $request) {
        $eventItem = $this->getEventItem($request->piece_code, $request->event_id);

        if (gettype($eventItem) === 'array') {
            return back()->withErrors($eventItem);
        }

        $eventItem->status = 'checked-out';
        $eventItem->save();
        return back()->with('success', 'Item checked-out: ' . $request->piece_code);
    }

    public function checkinPiece(Request $request) {
        $eventItem = $this->getEventItem($request->piece_code, $request->event_id);

        if (gettype($eventItem) === 'array') {
            return back()->withErrors($eventItem);
        }

        $eventItem->status = 'checked-in';
        $eventItem->save();
        return back()->with('success', 'Item checked-in: ' . $request->piece_code);
    }

    public function completePiece(Request $request) {
        $eventItem = $this->getEventItem($request->piece_code, $request->event_id);

        if (gettype($eventItem) === 'array') {
            return back()->withErrors($eventItem);
        }

        $eventItem->status = 'completed';
        $eventItem->save();
        return back()->with('success', 'Item completed: ' . $request->piece_code);
    }


    public function removePiece(Request $request) {
        $eventItem = EventItem::find($request->eventItem_id);

        $eventItem->piece_id = null;
        $eventItem->status = 'reserved';
        $eventItem->save();
    }

    public function addGroup(Request $request, $id) {
        DB::transaction(function() use ($request, $id) {
            foreach($request->pieces as $piece) {
                $availablePiece = EventItem::where('event_id', $id)->where('piece_id', $piece['id'])->get();
                if (count($availablePiece)) {
                    return back()->withErrors(['unable' => 'Group contains piece that has already been allocated to this event']);
                }

                $eventItem = EventItem::where('event_id', $id)->where('piece_id', null)->where('item_id', $piece['item_id'])->first();
                if ($eventItem) {
                    $eventItem->status = 'allocated';
                    $eventItem->piece_id = $piece['id'];
                } else {
                    $eventItem = new EventItem;

                    $eventItem->item_id = $piece['item_id'];
                    $eventItem->event_id = $id;
                    $eventItem->status = 'allocated';
                    $eventItem->piece_id = $piece['id'];
                }

                $eventItem->save();
            }
            Group::destroy($request->id);
        });
    }

    // INTERNAL FUNCTIONS

    private function getEventItem(String $code, String $event_id) {
        $piece = Piece::firstWhere('code', $code);

        if ($piece === null) {
            return ['not_found' => "No piece was found with code $code"];
        }

        $eventItem = EventItem::where('event_id', $event_id)->where('piece_id', $piece->id)->first();

        if ($eventItem === null) {
            return ['not_found' => "No event item was found in this event with code $code"];
        }

        return $eventItem;
    }
}
