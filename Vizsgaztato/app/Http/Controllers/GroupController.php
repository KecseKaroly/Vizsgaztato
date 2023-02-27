<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\group_join_request;
use App\Models\group_inv;
use App\Models\groups_users;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = DB::table('groups')->select('*')
            ->whereIn('id', function ($query) {
                $query->select('group_id')->from('groups_users')->where('user_id', auth()->id());
            })
            ->get();
        foreach ($groups as $group) {
            $group->join_requests = group_join_request::where('group_id', $group->id)->groupBy('group_id')->count();
        }
        $inv_requests = group_inv::where('invited_id', auth()->id())->count();
        return view('groups.index', ['groups' => $groups, 'inv_requests' => $inv_requests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invCode = Str::random(15);
        while (group::where('invCode', $invCode)->exists()) {
            $invCode = Str::random(15);
        }
        return view('groups.create')->with('invCode', $invCode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoregroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (group::where('invCode', $request->group_invCode)->exists()) {
            return redirect()->route('groups.index');
        }

        $group = new group;
        $group->name = $request->group_name;
        $group->invCode = $request->group_invCode;
        $group->creator_id = auth()->id();
        $group->save();

        $groups_users = new groups_users;
        $groups_users->user_id = auth()->id();
        $groups_users->group_id = $group->id;
        $groups_users->is_admin = true;
        $groups_users->save();

        Alert::success('Csoport sikeresen létrehozva!');
        return redirect()->route('groups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function show(group $group)
    {
        $this->authorize('view', $group);
        $groups = $group->load('users');
        $is_admin = groups_users::where(['user_id' => auth()->id(), 'group_id' => $group->id])->first()->is_admin;
        return view('groups.show', ['groups' => $groups, 'group' => $group, 'isAdmin' => $is_admin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(group $group)
    {
        $this->authorize('update', $group);
        return view('groups.edit')->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdategroupRequest $request
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, group $group)
    {
        $this->authorize('update', $group);
        $group->name = $request->group_name;
        $group->save();
        Alert::success('Csoport sikeresen módosítva!');
        return redirect()->route('groups.show', $group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(group $group)
    {
            $this->authorize('delete', $group);
            $group->delete();
            Alert::success('Csoport sikeresen törölve!');
            return redirect()->route('groups.index');
    }
}
