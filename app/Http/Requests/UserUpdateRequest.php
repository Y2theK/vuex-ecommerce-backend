<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            
            'email' => ['required',Rule::unique('users')->ignore($this->user)],
            // 'password' => [Rule::when(request()->isMethod('POST'), 'required'),'string','min:6'],
            'phone' => 'required|string',
            'company' => 'string|nullable',
            'address' => 'string | nullable',
            'city' => 'string | nullable',
            'state' => 'string | nullable',
            'zip_code' => 'string | nullable',
        ];
    }
}
