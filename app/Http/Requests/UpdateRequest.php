<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest {
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
            'active' => 'required',
            'role' => 'required',
            'type' => 'required',
            'licences' => 'required',
        ];
    }
}
