<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['RestrictedRegistration'];
    protected $casts = ['RestrictedRegistration' => 'boolean'];
    public $timestamps = false;

    use HasFactory;
}
