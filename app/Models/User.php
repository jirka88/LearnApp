<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Sluggable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'role_id',
        'type_id',
        'licences_id',
        'password',
        'active',
        'slug',
        'canShare',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected $append = ['get_count_users'];
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'firstname'
            ]
        ];
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    protected function getCountUsers():Attribute
    {
        return new Attribute(
            get: fn () => $this->all()->count()
        );
    }
    public function getUserBySlug($slug) : ?User {
        return $this->where('slug', $slug)->firstOrFail();
    }

    /**
     * Vrátí počet uživatelů podle role
     * @param $role
     * @return int|null
     */
    public function getUserCountByRole($role) : ?int {
        return $this->where('role_id', $role)->get()->count();
    }
    public function roles() : BelongsTo {
        return $this->belongsTo(Roles::class, 'role_id');
    }
    public function licences() : BelongsTo {
        return $this->belongsTo(Licences::class, 'licences_id');
    }
    public function accountTypes() :BelongsTo {
        return $this->belongsTo(AccountTypes::class, 'type_id');
    }
    public function patritions() : BelongsToMany {
        return $this->belongsToMany(Partition::class, 'userPartition', 'user_id','partition_id')
            ->as('permission')
            ->withPivot(['accepted', 'permission_id']);
    }
}
