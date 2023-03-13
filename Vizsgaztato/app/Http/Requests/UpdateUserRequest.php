<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',  \Illuminate\Validation\Rule::unique('users')->ignore($this->user()->id)],
            'password' => ['sometimes', 'required', 'min:8', 'confirmed'],
            'password-confirm' => ['sometimes','required_with:password|same:password'],
        ];
    }

    public function messages() {
        return [
            'username.required' => 'Felhasználónév megadása kötelező!',
            'username.max' => 'A felhasználónév hossza nem lehet több, mint 255 karakter!',
            'name.required' => 'Név megadása kötelező!',
            'name.max' => 'A nevének hossza nem lehet több, mint 255 karakter!',
            'email.required' => 'Email megadása kötelező!',
            'email.email' => 'Email formátuma nem megfelelő!',
            'email.max' => 'A email címének hossza nem lehet több, mint 255 karakter!',
            'email.unique' => 'A megadott email cím már foglalt!',
            'password.min' => 'Jelszó minimum 8 karakter hosszú!',
            'password.confirmed' => 'A jelszavak nem egyeznek!',
        ];
    }
}
