<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'active',
        'lock_on_expire',
        'expires_at',
        'full_access',
        'manage_nested',
        'keep_when_changing_unit',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected function ownerName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => $value
                ? __("by") . " " . $attributes['owner']
                : null,
        );
    }

    protected function insertedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => $value
                ? __("Inserted at") . " " . date_format(date_create($attributes['created_at']), "d/m/y H:i") . " " . __("by") . " " . User::find($attributes['owner'])->name
                : null,
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => $value
                ? __("Created at") . " " . date_format(date_create($value), "d/m/y H:i") . " " . __("by") . " " . User::find($attributes['owner'])->name
                : null,
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => $value
                ? __("Updated at") . " " . date_format(date_create($value), "d/m/y H:i") . " " . __("by") . " " . User::find($attributes['owner'])->name
                : null,
        );
    }

    protected function deletedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value = null, array $attributes) => $value
                ? __("Deleted at") . " " . date_format(date_create($value), "d/m/y H:i") . " " . __("by") . " " . User::find($attributes['owner'])->name
                : null,
        );
    }
}
