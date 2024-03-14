<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFormRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Piece;
use App\Models\EventItem;

class GroupController extends Controller
{
    public function index(Request $request) {
        $groups = Group::all();
        $groups = $groups->map(function($group) {
            $group->pieces = $group->pieces;
            $group->pieces = $group->pieces->map(function($piece) {
                $piece->item = $piece->item;
                return $piece;
            });
            return $group;
        });

        return Inertia::render('Groups/Index', [
            'groups' => $groups,
        ]);
    }

    public function store(GroupFormRequest $request) {
        $group = new Group;
        $group->title = $request->title;
        $containerPiece = Piece::firstWhere('code', $request->container_piece_code);

        $current = EventItem::where('piece_id', $containerPiece->id)->where('status', '!=', 'completed')->get();
        if (count($current)) {
            return back()->withErrors(['unable' => 'Unable to add piece to group whilst in use on an event']);
        }
        $group->container_piece_id = $containerPiece->id;
        $group->save();

        $containerPiece->group_id = $group->id;
        $containerPiece->save();

        return redirect('/groups');
    }

    public function edit(Request $request, $id) {
        $group = Group::find($id);
        $group->container = $group->container();
        $group->pieces = $group->pieces;
        $group->pieces = $group->pieces->map(function($piece) {
            $piece->item = $piece->item;
            return $piece;
        });

        return Inertia::render('Groups/Group', [
            'group' => $group,
        ]);
    }

    public function update(GroupFormRequest $request, $id) {
        $group = Group::find($id);

        $group->title = $request->title;
        $containerPiece = Piece::firstWhere('code', $request->container_piece_code);
        $group->container_piece_id = $containerPiece->id;
        $group->save();

        $containerPiece->group_id = $group->id;
        $containerPiece->save();

        return redirect('/groups');
    }

    public function destroy(Request $request, $id) {
        Group::destroy($id);

        return redirect('/groups');
    }

    public function addPiece(Request $request, $id) {
        $piece = Piece::firstWhere('code', $request->piece_code);
        if (! $piece) {
            return back()->withErrors(['not_found' => 'No item piece found with that code']);
        }
        $current = EventItem::where('piece_id', $piece->id)->where('status', '!=', 'completed')->get();
        if (count($current)) {
            return back()->withErrors(['unable' => 'Unable to add piece to group whilst in use on an event']);
        }

        $piece->group_id = $id;
        $piece->save();

        return back();
    }

    public function removePiece(Request $request, $id) {
        $piece = Piece::find($request->id);
        $group = Group::find($id);
        if ($group->container_piece_id === $piece->id) {
            return back()->withErrors(['unable' => 'Cannot remove the container of this group']);
        }
        $piece->group_id = null;
        $piece->save();

        return back();
    }
}
