<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licences extends Model
{
    use HasFactory;
    public $timestamps = false;

    public const standartUserPartitions = 10;
    public const standartUserChaptersInPartitions = 10;
    public const standartPlusUserPartitions = 40;

    public function Users() : HasMany {
        return $this->HasMany(User::class);
    }
}
