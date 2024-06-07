<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSettingType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner',
        'name',
        'label',
        'description',
        'translations',
        'mask',
        'validation',
        'validate',
        'required',
        'values',
        'default_value',
    ];

    public function settings(): BelongsTo
    {
        return $this->belongsTo(UserSetting::class);
    }
}
