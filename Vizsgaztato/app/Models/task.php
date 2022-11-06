<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    use HasFactory;
    protected $table = 'tasks';

    protected $primaryKey = 'id';
    
    protected $fillable = ['test_id', 'text', 'type'];

    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany(question::class);
    }
}
