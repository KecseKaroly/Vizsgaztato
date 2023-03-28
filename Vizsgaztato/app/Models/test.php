<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'id';

    protected $fillable = [
        'is_exam',
        'title',
        'maxAttempts',
        'resultsViewable',
        'duration',
        'creator_id',
        'course_id',
        'module_id',
        'enabled_from',
        'enabled_until',];

    public $timestamps = true;

    public function questions(): HasMany
    {
        return $this->hasMany(question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class, 'creator_id', 'id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(attempt::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
