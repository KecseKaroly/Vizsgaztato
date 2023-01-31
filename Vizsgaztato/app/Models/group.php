<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $primaryKey = 'id';

    protected $fillable = ['invCode','name', 'creator_id'];

    public $timestamps = false;
}
