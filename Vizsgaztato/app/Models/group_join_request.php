<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group_join_request extends Model
{
    use HasFactory;

    protected $table = 'group_join_requests';

    protected $primaryKey = 'id';

    protected $fillable = ['requester_id', 'group_id'];

    public $timestamps = false;
}
