<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'topic', 'course_id'];

    public $timestamps = false;

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function quiz() : HasOne {
        return $this->hasMany(Quiz::class);
    }
}
