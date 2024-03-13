<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFormRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function index(Request $request) {
        $groups = Group::all();

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
}
