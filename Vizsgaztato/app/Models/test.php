<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'id';
    
    protected $fillable = ['title','maxAttempts'];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(task::class);
    }
}
