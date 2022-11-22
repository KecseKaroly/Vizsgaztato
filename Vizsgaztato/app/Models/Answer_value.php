<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer_value extends Model
{
    use HasFactory;
    protected $table = 'answer_values';

    protected $primaryKey = 'id';
    
    protected $fillable = ['text'];

    public $timestamps = false;
}
