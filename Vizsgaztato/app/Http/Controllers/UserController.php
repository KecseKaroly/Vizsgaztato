<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Alert;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create() {
        return view('auth.registerTeacher');
    }

    public function store(StoreUserRequest $request) {
        try{
            User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'auth' => 0,
            ]);
            Alert::success('Tanár sikeresen rögzítve!');
            return redirect()->back();
        }
        catch(\Exception $exception) {
            Alert::warning('Valami hiba történt!');
            return redirect()->route('home');
        }
    }

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
