<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
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
            'group_name' => 'required|min:3|max:30',
        ];
    }

    public function messages() {
        return [
            'group_name.required' => 'A csoport nevének megadása kötelező!',
            'group_name.min'=>'A csoport nevének hosszának minimum 3 karakternek kell lennie!',
            'group_name.max'=>'A csoport nevének hossza maximum 30 karakter lehet!',
        ];
    }
}
