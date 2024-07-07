<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use App\Traits\RelationsManager;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword {
    use HasApiTokens, HasFactory, Notifiable, Sluggable, FilterQueryString;

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
        'image',
        'user_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $append = ['get_count_users'];

    protected $filters = ['sort'];

    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'firstname',
            ],
        ];
    }
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    protected function getCountUsers(): Attribute {
        return new Attribute(
            get: fn () => $this->where('user_active', 1)->count()
        );
    }

    public function getUserByEmail($email): ?User {
        return $this->where('email', $email)->firstOrFail();
    }

    public function getUserBySlug($slug): ?User {
        return $this->where('slug', $slug)->firstOrFail();
    }

    public function getUserById($id): ?User {
        return $this->find($id);
    }

    /**
     * Vrátí počet uživatelů podle role
     */
    public function getUserCountByRole($role): ?int {
        return $this->where('role_id', $role)->where('user_active', 1)->get()->count();
    }

    public function roles(): BelongsTo {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function licences(): BelongsTo {
        return $this->belongsTo(Licences::class, 'licences_id');
    }

    public function accountTypes(): BelongsTo {
        return $this->belongsTo(AccountTypes::class, 'type_id');
    }

    public function patritions(): BelongsToMany {
        return $this->belongsToMany(Partition::class, 'userPartition', 'user_id', 'partition_id')
            ->as('permission')
            ->withPivot(['accepted', 'permission_id']);
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
