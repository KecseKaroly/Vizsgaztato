<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group_inv extends Model
{
    use HasFactory;

    protected $table = 'group_invs';

    protected $primaryKey = 'id';

    protected $fillable = ['sender_id', 'group_id', 'invited_id'];

    public $timestamps = false;
}
