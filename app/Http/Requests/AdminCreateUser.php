<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateUser extends FormRequest
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
            'firstname' => 'required|min:3|max:25',
            'lastname' => 'required|max:50',
            'email' => 'required|email|unique:users|max:320',
            'type' => 'required',
            'role' => 'required',
            'licence' => 'required',
            'password' => 'required|min:8'
        ];
    }
    public function messages()
    {
        return [
            'firstname.min' => 'Vaše jméno je příliš krátké.',
            'firstname.max' => 'Jméno je příliš dlouhé.',
            'email.required' => '0',
            'email.email' => 'E-mail musí být platný.',
            'email.unique' => 'E-mail je již registrován.',
            'email.max' => 'E-mail může mít maximálně 320 znaků.',
            ];
    }
}
