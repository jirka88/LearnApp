<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;
    use sluggable;
    protected $fillable = [
        'name',
        'perex',
        'context',
        'slug',
        'partition_id'
    ];
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Vrátí kapitolu podle slug
     * @param $chapter
     * @return mixed
     */
    public function getChapter($chapter) :Chapter {
        return $this->where('slug', $chapter)->first();
    }
    public function Partition() :BelongsTo {
        return $this->BelongsTo(Partition::class, 'partition_id');
    }
}
