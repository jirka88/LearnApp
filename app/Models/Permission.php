<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model {
    use HasFactory;

    protected $fillable = ['permission'];

    protected $casts = ['accepted' => 'boolean'];

    public $timestamps = false;

    public function userPartitions(): HasMany {
        return $this->HasMany(UserPartition::class);
    }
}
