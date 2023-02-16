<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'maxAttempts', 'duration', 'creator_id'];

    public $timestamps = true;

    public function tasks()
    {
        return $this->hasMany(task::class);
    }

    public function groups()
    {
        return $this->belongsToMany(
            group::class,
            'tests_groups',
            'test_id',
            'group_id'
        )
            ->withPivot(['enabled_from', 'enabled_until']);
    }
}
