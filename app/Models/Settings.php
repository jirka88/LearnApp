<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Settings extends Model {

    use LogsActivity;

    public $timestamps = false;

    protected $casts = [
        'RestrictedRegistration' => 'boolean',
    ];
    protected $fillable = [
        'RestrictedRegistration',
        'color',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->setDescriptionForEvent(fn(string $eventName) => "Změna nastavení");
    }

    use HasFactory;
}
