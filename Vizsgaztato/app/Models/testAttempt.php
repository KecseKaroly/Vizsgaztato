<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testAttempt extends Model
{
    use HasFactory;
    protected $table = 'test_attempts';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'test_id', 'maxScore', 'achievedScore'];

    public $timestamps = true;
}
