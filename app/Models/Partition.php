<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partition extends Model
{
    use HasFactory;
    use Sluggable;
    protected $fillable = ['name', 'created_by', 'icon', 'slug'];
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    public function Users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'userPartition', 'partition_id','user_id');
    }
    public function Chapter() :HasMany {
        return $this->HasMany(Chapter::class);
    }
}
