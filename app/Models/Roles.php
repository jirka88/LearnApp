<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Roles extends Model
{
    use HasFactory;

    protected $fillable = ["role"];

    public function Users(): BelongsTo {
        return $this->belongsTo('users','role_id');
    }
}
