<?php

namespace App\Http\Requests;

use App\Rules\ReCaptcha;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'firstname' => 'required|min:3|max:25',
            'lastname' => 'required|max:50',
            'email' => 'required|email|unique:users|max:64',
            'type' => 'required',
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
            'confirm' => 'accepted',
            'token' => [new ReCaptcha],
        ];
    }

    public function messages() {
        return [
            'firstname.min' => trans('validation.min.string', ['attribute' => __('authentication.register.name'), 'min' => 3]),
            'email.unique' => ['unique' => __('validation.custom.email.registered')],
            'email.max' => 'E-mail může mít maximálně 64 znaků.',
            'confirm.accepted' => 'Musíte souhlasit ze zpracováním údajů!',
        ];
    }
}
