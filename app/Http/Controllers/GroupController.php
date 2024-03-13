<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFormRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Piece;

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
        $group->container_piece_id = $request->container_piece_id;

        $group->save();
        return redirect('/groups');
    }

    public function edit(Request $request, $id) {
        $group = Group::find($id);
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
        $group->container_piece_id = $request->container_piece_id;
        $group->save();

        return redirect('/groups');
    }

    public function destroy(Request $request, $id) {
        Group::destroy($id);

        return redirect('/groups');
    }

    public function addPiece(Request $request, $id) {
        $piece = Piece::firstWhere('code', $request->piece_code);
        $piece->group_id = $id;
        $piece->save();

        return back();
    }

    public function removePiece(Request $request, $id) {
        $piece = Piece::find($request->id);
        $piece->group_id = null;
        $piece->save();

        return back();
    }
}
