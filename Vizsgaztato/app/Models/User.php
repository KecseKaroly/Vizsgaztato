<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable  implements  \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'auth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function attempts()
    {
        return $this->hasMany(testAttempt::class);
    }
    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(
            group::class,
            'groups_users',
            'user_id',
            'group_id')
            ->withPivot(['is_admin']);
    }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function courses() : BelongsToMany {
        return $this->belongsToMany(
            Course::class,
            'courses_users',
            'user_id',
            'course_id'
        );
    }
}
