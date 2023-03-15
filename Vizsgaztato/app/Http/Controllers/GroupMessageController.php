<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\group;
use Illuminate\Http\Request;
use App\Models\GroupMessage;
class GroupMessageController extends Controller
{
    public function index(group $group)
    {
        return view('groups.chat', ['group'=>$group->load('groupMessages.user')]);
    }

    public function latest(group $group)
    {
        return GroupMessage::with('user')->latest()->first();
    }

    public function store(Request $request, group $group)
    {
        $message =new GroupMessage([
            'message' => $request->message,
            'group_id' => $group->id,
            'user_id' => auth()->id(),
        ]);
        $message->save();
        broadcast(new GroupMessageSent($group->id))->toOthers();
        return $message->load('user');
    }
}
