<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:150',
            'email' => 'required|email:filter|max:150|unique:users',
            'password' => 'required|max:150|min:8',
            'image' => 'mimes:jpg,bmp,png,gif,jpeg,webp|max:5000',
            'short_description' => 'required|max:500'
        ];
    }
}
