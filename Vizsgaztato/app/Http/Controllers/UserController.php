<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user) {
        return view('users.show', ['user'=>$user]);
    }

    public function update(UpdateUserRequest $request, User $user) {
        $user->update(
            $request->validated()
        );
        return redirect()->back();
    }
}
