<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesGroups extends Model
{
    use HasFactory;
    protected $table = 'courses_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['group_id', 'course_id'];

    public $timestamps = true;
}
