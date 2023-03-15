<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // $user = auth()->user();
        return [
            'name' => 'required|string',
            // 'email' => ['required','unique:users,email'.$this->user->id],
            'email' => 'required|email|unique:users,email',
            'password' => [Rule::when(request()->isMethod('POST'), 'required'),'string','min:6'],
            'phone' => 'required|string',
            'company' => 'string|nullable',
        ];
    }
}
