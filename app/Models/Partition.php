<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function permissions() :BelongsTo {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Vrátí objekt předmětu podle slug
     * @param $slug
     * @return Partition|null
     */
    public function getSubjectBySlug($slug): ?Partition {
        return $this->where('slug', $slug)->firstOrFail();
    }

    /**
     * Vrátí objekt předmětu podle ID
     * @param $id
     * @return Partition|null
     */
    public function getSubjectById($id): ?Partition {
        return $this->findOrFail($id);
    }
    /**
     * Vrátí Id předmětu/sekce
     * @param $slug
     * @return int|null
     */
    public function getSubjectId($slug) : ?int {
        return $this->where('slug', $slug)->pluck('id')->firstOrFail();
    }
    protected function chapterCount() :Attribute
    {
        return new Attribute(
            get: fn () => $this->Chapter()->count()
        );
    }
}
