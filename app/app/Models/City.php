<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
        'latitude',
        'longitude',
        'flag',
        'wikidataid',
        'coordinates',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
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
