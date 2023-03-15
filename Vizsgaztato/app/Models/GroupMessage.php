<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMessage extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'user_id', 'group_id'];

    public $timestamps = true;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function group() : BelongsTo {
        return $this->belongsTo(group::class);
    }
}
