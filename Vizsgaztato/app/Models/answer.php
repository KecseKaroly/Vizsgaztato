<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answer extends Model
{
    use HasFactory;
    protected $table = 'answers';

    protected $primaryKey = 'id';
    
    protected $fillable = ['question_id', 'text', 'solution', 'score'];

    public $timestamps = false;
}
