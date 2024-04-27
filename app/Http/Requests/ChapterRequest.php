<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterRequest extends FormRequest
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
            "name" => "required|max:20",
            "perex" => "max:50",
            "contentChapter" => "required",
            "slug" => "required"
        ];
    }
    public function messages()
    {
        return [
            "content.required" => "Musíte mít obsah!",
        ];
    }
}
