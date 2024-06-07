<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner',
        'type',
        'value',
        'category',
        'issued_at',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'city_id',
        'city_name',
        'expires_at',
        'complementary_data',
        'primary',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(UserDocumentType::class, 'id', 'type');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    protected function issued(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => __("Issued at") . " " . date_format(date_create($attributes['issued_at']), "d/m/Y"),
        );
    }

    protected function expires(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => __("Expires at") . " " . date_format(date_create($attributes['expires_at']), "d/m/Y"),
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => $value
                ? __("Created at") . " " . date_format(date_create($value), "d/m/y H:i")
                : null,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => $value
                ? __("Updated at") . " " . date_format(date_create($value), "d/m/y H:i")
                : null,
        );
    }

    protected function deletedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null) => $value
                ? __("Deleted at") . " " . date_format(date_create($value), "d/m/y H:i")
                : null,
        );
    }
}
