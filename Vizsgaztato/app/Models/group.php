<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $primaryKey = 'id';

    protected $fillable = ['invCode', 'name', 'creator_id'];

    public $timestamps = false;

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'groups_users',
            'group_id',
            'user_id')
            ->withPivot(['id', 'is_admin'])
            ->orderBy('is_admin', 'desc')
            ->orderBy('users.name');
    }

    public function courses() : BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'courses_groups',
            'group_id',
            'course_id');
    }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class);
    }
}
