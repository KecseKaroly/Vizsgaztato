<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:30',
            'invCode' => 'required|string|size:15|unique:groups',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'A csoport nevének megadása kötelező!',
            'name.min'=>'A csoport nevének hossza minimum 3 karakternek kell lennie!',
            'name.max'=>'A csoport nevének hossza maximum 30 karakter lehet!',
            'invCode.required' => 'A meghívó kód nem lehet üres!',
            'invCode.size' => 'A meghívó kódnak 15 karakter hosszúságúnak kell lennie!',
            'invCode.unique' => 'A meghívó kódnak egyedinek kell lennie!',
        ];
    }
}
