<?php

namespace App\Traits;

use App\Models\Chapter;
use App\Models\Partition;

trait testTrait
{
    public function createSubject($user) {
        $subject = Partition::factory()->create([
            "created_by" => $user,
        ]);
        return $subject;
    }
    public function createChapter($subject) {
        $chapter = Chapter::factory()->create([
            "partition_id" => $subject->id,
        ]);
        return $chapter;
    }
}
