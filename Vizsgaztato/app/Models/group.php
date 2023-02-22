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
            ->withPivot(['id', 'role']);
    }
    public function tests() : BelongsToMany
    {
        return $this->belongsToMany(
            test::class,
            'tests_groups',
            'group_id',
            'test_id')
            ->withPivot('enabled_from', 'enabled_until');
    }
}
