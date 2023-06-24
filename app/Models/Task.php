<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'deadline',
        'is_finished',
    ];


    /**
     * Belongs to a single user.
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}