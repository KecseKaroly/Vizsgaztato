<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class given_answer extends Model
{
    use HasFactory;
    protected $table = 'given_answers';

    protected $primaryKey = 'id';

    protected $fillable = ['attempt_id', 'option_id', 'answer_id'];

    public $timestamps = false;

    public function option() : BelongsTo
    {
        return $this->belongsTo(option::class);
    }

    public function answer() : HasOne
    {
        return $this->hasOne(answer::class, 'id', 'answer_id');
    }
}
