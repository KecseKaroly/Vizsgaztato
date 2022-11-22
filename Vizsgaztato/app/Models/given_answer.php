<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class given_answer extends Model
{
    use HasFactory;
    protected $table = 'given_answers';

    protected $primaryKey = 'id';
    
    protected $fillable = ['attempt_id', 'question_id', 'answer_id', 'given_id'];

    public $timestamps = false;
}
