<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model {
    protected $fillable = [
        'RestrictedRegistration',
        'color',
    ];

    public $timestamps = false;

    protected $casts = [
        'RestrictedRegistration' => 'boolean',
    ];

    use HasFactory;
}
