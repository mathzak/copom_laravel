<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserContactType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner',
        'name',
        'mask',
        'validator',
        'required',
    ];

    public function contacts(): BelongsTo
    {
        return $this->belongsTo(UserContact::class);
    }
}
