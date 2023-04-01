<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class question extends Model
{
    use HasFactory;
    protected $table = 'questions';

    protected $primaryKey = 'id';

    protected $fillable = ['test_id', 'text', 'type'];

    public $timestamps = false;

    public function options() : HasMany
    {
        return $this->hasMany(option::class);
    }

    public function test()  : BelongsTo
    {
        return $this->belongsTo(test::class);
    }
}
