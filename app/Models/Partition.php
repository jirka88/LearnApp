<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partition extends Model
{
    use HasFactory;
    use Sluggable;
    protected $fillable = ['name', 'created_by', 'icon', 'slug'];

    protected $appends = ['chapter_count'];
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function Users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'userPartition', 'partition_id','user_id')
            ->as('permission')
            ->withPivot(['accepted', 'permission_id']);
    }
    public function Chapter() :HasMany {
        return $this->HasMany(Chapter::class);
    }
    protected function chapterCount() :Attribute
    {
        return new Attribute(
            get: fn () => $this->Chapter()->count()
        );
    }
}
