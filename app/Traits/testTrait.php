<?php

namespace App\Traits;

use App\Models\Chapter;
use App\Models\Partition;
use App\Models\User;

trait testTrait
{
    /**
     * Vytvoří náhodný předmět
     * @param $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function createSubject($user) {
        $subject = Partition::factory()->create([
            "created_by" => $user,
        ]);
        return $subject;
    }

    /**
     * Vytvoří náhodnou kapitolu k předmětu
     * @param $subject
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function createChapter($subject) {
        $chapter = Chapter::factory()->create([
            "partition_id" => $subject->id,
        ]);
        return $chapter;
    }
    public function createUser($role) {
        if($role === 0) {
            $user = User::factory()->create();
        }
        else {
            $user = User::factory()->create([
                "role_id" => $role
            ]);
        }
        return $user;
    }
}
