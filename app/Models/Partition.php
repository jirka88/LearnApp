<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partition extends Model
{
    use HasFactory;
    protected $fillable = ["name", 'created_by', "icon"];

    public function Users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'userPartition', 'partition_id','user_id');
    }
    public function Chapters() :HasMany {
        return $this->HasMany(Chapter::class, 'partition_id');
    }
}
