<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class groups_users extends Model
{
    use HasFactory;
    protected $table = 'groups_users';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'group_id', 'role'];

    public $timestamps = false;
}
