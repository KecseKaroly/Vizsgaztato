<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\group;
use App\Models\group_join_request;
use App\Models\group_inv;
use App\Models\groups_users;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Events\GroupCreated;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = auth()->user()->groups()->paginate(3);
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
        try{
            $this->authorize('create', group::class);
            $invCode = Str::random(15);
            while (group::where('invCode', $invCode)->exists()) {
                $invCode = Str::random(15);
            }
            return view('groups.create')->with('invCode', $invCode);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoregroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupRequest $request)
    {
        try{
            $this->authorize('create', group::class);
            $validated = $request->safe()->merge(['creator_id' => auth()->id()])->all();

            $group = group::create($validated);

            // Hozzáadom az új csoporthoz adminként
            event(new GroupCreated($group));

            Alert::success('Csoport sikeresen létrehozva!');
            return redirect()->route('groups.index');
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function show(group $group)
    {
        try{
            $this->authorize('view', $group);
            $users = $group->users()->paginate(5);
            $is_admin = groups_users::where(['user_id' => auth()->id(), 'group_id' => $group->id])->first()->is_admin;
            return view('groups.show', ['users' => $users, 'group' => $group, 'isAdmin' => $is_admin]);

        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(group $group)
    {
        try{
            $this->authorize('update', $group);
            return view('groups.edit')->with('group', $group);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdategroupRequest $request
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, group $group)
    {
        try{
            $this->authorize('update', $group);
            $group->name = $request->group_name;
            $group->save();
            Alert::success('Csoport sikeresen módosítva!');
            return redirect()->route('groups.show', $group);
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(group $group)
    {
        try{
            $this->authorize('delete', $group);
            $group->delete();
            Alert::success('Csoport sikeresen törölve!');
            return redirect()->route('groups.index');
        }
        catch(AuthorizationException $exception) {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }
}
