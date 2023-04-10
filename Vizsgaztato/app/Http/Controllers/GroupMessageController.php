<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Models\GroupMessage;
use Illuminate\Validation\UnauthorizedException;
use RealRashid\SweetAlert\Facades\Alert;

class GroupMessageController extends Controller
{
    public function index(group $group)
    {
        try{
            $this->authorize('view', $group);
            return view('groups.chat', ['group'=>$group->load('groupMessages.user')]);
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }
    }

    public function latest(group $group)
    {
        return GroupMessage::with('user')->latest()->first();
    }

    public function store(Request $request, group $group)
    {
        try{
            $this->authorize('view', $group);
            $message =new GroupMessage([
                'message' => $request->message,
                'group_id' => $group->id,
                'user_id' => auth()->id(),
            ]);
            $message->save();
            broadcast(new GroupMessageSent($group->id))->toOthers();
            return $message;
        }
        catch(AuthorizationException $exception)
        {
            Alert::warning($exception->getMessage());
            return redirect()->route('groups.index');
        }

    }
}
