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
use Illuminate\Support\Facades\Session;


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
                $query->select('group_id')->from('groups_users')->where('user_id', Auth::id());
            })
            ->get();
        foreach ($groups as $group) {
            $group->join_requests = group_join_request::where('group_id', $group->id)->groupBy('group_id')->count();
        }
        $inv_requests = group_inv::where('invited_id', Auth::id())->count();
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
        $group->creator_id = Auth::id();
        $group->save();

        $groups_users = new groups_users;
        $groups_users->user_id = Auth::id();
        $groups_users->group_id = $group->id;
        $groups_users->role = "admin";
        $groups_users->save();

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
        $groups = $group->load('users');
        $myRole = groups_users::where(['user_id' => Auth::id(), 'group_id' => $group->id])->first()->role;
        return view('groups.show', ['groups' => $groups, 'group' => $group, 'myRole' => $myRole]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(group $group)
    {
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
        $group->name = $request->group_name;
        $group->save();
        return back()->with('message', 'Record Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(group $group)
    {
        $group->delete();
        return redirect()->route('groups.index');
    }
}
