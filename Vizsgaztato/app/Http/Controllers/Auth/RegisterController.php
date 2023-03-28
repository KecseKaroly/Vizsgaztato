<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'acceptTOS'=>['required'],
        ],
        [
            'username.required' => 'Felhasználónév megadása kötelező!',
            'username.max' => 'A felhasználónév hossza nem lehet több, mint 255 karakter!',
            'name.required' => 'Név megadása kötelező!',
            'name.max' => 'A nevének hossza nem lehet több, mint 255 karakter!',
            'email.required' => 'Email megadása kötelező!',
            'email.email' => 'Email formátuma nem megfelelő!',
            'email.max' => 'A email címének hossza nem lehet több, mint 255 karakter!',
            'email.unique' => 'A megadott email cím már foglalt!',
            'password.required' => 'Jelszó megadása kötelező!',
            'password.min' => 'Jelszó minimum 8 karakter hosszú!',
            'password.confirmed' => 'A jelszavak nem egyeznek!',
            'acceptTOS.required' => 'A felhasználási feltételek elfogadása kötelező!',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
