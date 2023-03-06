<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesUsers extends Model
{
    use HasFactory;
    protected $table = 'courses_users';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'course_id'];

    public $timestamps = true;
}
