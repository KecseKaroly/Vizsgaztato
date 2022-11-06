<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;
    protected $table = 'questions';

    protected $primaryKey = 'id';
    
    protected $fillable = ['task_id', 'text'];

    public $timestamps = false;

    public function answers()
    {
        return $this->hasMany(answer::class);
    }
}