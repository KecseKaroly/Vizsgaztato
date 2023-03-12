<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Module extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'modules';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'topic', 'material', 'course_id'];

    public $timestamps = false;

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function quizzes() : BelongsToMany {
        return $this->belongsToMany(
            test::class,
            'courses_quizzes',
            'module_id',
            'test_id');
    }
}
