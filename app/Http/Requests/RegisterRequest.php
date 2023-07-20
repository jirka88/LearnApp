<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users|max:320',
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
            'confirm' => 'accepted',
        ];
    }
    public function messages() {
        return [
            'firstname.required' => "Jméno je povinné pole.",
            'firstname.min' => 'Vaše jméno je příliš krátké.',
            'firstname.max' => 'Jméno je příliš dlouhé.',
            'email.required' => '0',
            'email.email' => 'E-mail musí být platný.',
            'email.unique' => 'E-mail je již registrován.',
            'email.max' => 'E-mail může mít maximálně 320 znaků.',
            'password_confirm.required' => 'Potvrzení hesla je povinné pole.',
            'password.min' => 'Heslo musí mít alespoň 8 znaků.',
            'password_confirm.same' => 'Potvrzení hesla se neshoduje s heslem.',
            'confirm.accepted' => 'Musíte souhlasit ze zpracováním údajů!',
        ];
    }
}
