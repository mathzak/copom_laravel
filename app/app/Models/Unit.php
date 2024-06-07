<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Unit extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships;

    protected $fillable = [
        'owner',
        'name',
        'shortpath',
        'fullpath',
        'parent_id',
        'order',
        'children_id',
        'nickname',
        'founded',
        'active',
        'expires_at',
        'cellphone',
        'landline',
        'email',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'city_id',
        'city_name',
        'postcode',
        'address',
        'complement',
        'latitude',
        'longitude',
        'coordinates',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getDescendants(): Collection
    {
        return $this->descendantsAndSelf->pluck('id');
    }

    public function getTotalUsers(): Unit
    {
        return Unit::join('unit_user', 'units.id', '=', 'unit_user.unit_id')
            ->whereIn('units.id', $this->descendantsAndSelf->pluck('id'))
            ->count('unit_user.user_id');
    }

    public function getParentsNames(): string
    {
        if ($this->parent) {
            return $this->parent->getParentsNames() . ' > ' . $this->name;
        } else {
            return $this->name;
        }
    }

    public function getParentsNicknames(): string
    {
        if ($this->parent) {
            return $this->parent->getParentsNames() . ' > ' . $this->nickname;
        } else {
            return $this->nickname;
        }
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
