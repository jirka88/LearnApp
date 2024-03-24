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
    public function Partition() :BelongsTo {
        return $this->BelongsTo(Partition::class, 'partition_id');
    }
    /**
     * Vrátí kapitolu podle slug
     * @param $chapter
     * @return mixed
     */
    public function getChapter($slug) :?Chapter {
        return $this->where('slug', $slug)->firstOrFail();
    }

    /**
     * Vrátí podle sekce id a jména kapitoly kapitolu
     * @param $id
     * @param $name
     * @return Chapter|null
     */
    public function getChapterByNameAndPatrition($partitionId, $name) :? Chapter{
        return $this->where("partition_id", $partitionId)->where("name", $name)->first();
    }
    public function getChapterWithPermission($slug) :? Chapter {
        return $this->where('slug', $slug)->with(['Partition.Users' => function ($query2) {
            $query2->where('user_id', auth()->user()->id)->firstOrFail();
        }])->firstOrFail();
    }
}
