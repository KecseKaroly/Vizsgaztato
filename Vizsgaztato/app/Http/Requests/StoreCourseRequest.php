<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'goal' => 'required|string|max:1000',
        ];
    }

    public function messages() {
        return [
            'title.required' => 'A kurzus megnevezésének megadása kötelező!',
            'title.min'=>'A kurzus megnevezésének hossza minimum 3 karakternek kell lennie!',
            'title.max'=>'A kurzus megnevezésének hossza maximum 30 karakter lehet!',
            'goal.required' => 'A kurzus céljának megadása kötelező!',
            'goal.max' => 'A kurzus céljának hossza maximum 1000 karakter lehet!',
        ];
    }
}
