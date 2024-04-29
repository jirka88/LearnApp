<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountTypes extends Model {
    use HasFactory;

    protected $fillable = ['type'];

    public $timestamps = false;

    public function users(): HasMany {
        return $this->HasMany(User::class);
    }
}
