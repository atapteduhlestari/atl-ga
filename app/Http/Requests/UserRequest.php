<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\Request;
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
        $rules = [
            'name' => 'required',
            'sbu_id' => 'required',
            'username' => ['required', Rule::unique('users')->ignore($this->user, 'id')],
            'email' => ['required', 'email:rfc', Rule::unique('users')->ignore($this->user, 'id')],
            'password' => 'nullable|min:6'
        ];

        if ($this->isMethod('POST'))
            $rules['password'] = 'required|min:6';

        return $rules;
    }
}
