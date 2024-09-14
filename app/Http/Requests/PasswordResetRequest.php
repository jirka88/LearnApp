<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest {
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
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[^A-Za-z0-9])/',
            'againNewPassword' => 'required|same:newPassword',
        ];
    }

    public function messages() {
        return [
            'againNewPassword.same' => 'Nová hesla se neshodují!',
        ];
    }
}
