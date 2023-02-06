<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestsGroups extends Model
{
    use HasFactory;
    protected $table = 'tests_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['test_id', 'group_id', 'enabled_from', 'enabled_until'];

    public $timestamps = false;
}
