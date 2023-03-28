<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'title' => 'required|string|min:3|max:30',
            'topic' => 'required|string|min:3|max:30',
            'material' => 'required',
        ];
    }

    public function messages() {
        return [
            'title.required' => 'A modul címének megadása kötelező!',
            'title.min'=>'A modul címének hossza minimum 3 karakternek kell lennie!',
            'title.max'=>'A modul címének hossza maximum 30 karakter lehet!',
            'topic.required' => 'A modul témakörének megadása kötelező!',
            'topic.min'=>'A modul témakörének hossza minimum 3 karakternek kell lennie!',
            'topic.max'=>'A modul témakörének hossza maximum 30 karakter lehet!',
            'material.required' => 'A tananyag megadása kötelező!',
        ];
    }
}
