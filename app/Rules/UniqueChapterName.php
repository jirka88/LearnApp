<?php

namespace App\Rules;

use App\Models\Chapter;
use Illuminate\Contracts\Validation\Rule;

class UniqueChapterName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $attribute;
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     * @param string $attribute
     * @param mixed $chapter
     * @return bool
     */
    public function passes($attribute, $chapter)
    {
        $this->attribute = $attribute;
        $chapterModel = app('\App\Models\Chapter');
        if($chapterModel->getChapterByNameAndPatrition($chapter->partition_id, $chapter->name) !== null) {
            if($chapterModel->getChapterById($chapter->id)->name === $chapter->name) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function message()
    {
        return [
            'name' => trans('validation.unique', ["attribute" => $this->attribute])];
    }
}
