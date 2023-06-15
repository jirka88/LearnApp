<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'context',
    ];
    public function Partition() :BelongsTo {
        return $this->BelongsTo(Partition::class, 'id');
    }
}
