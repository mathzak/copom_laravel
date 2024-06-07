<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'photo',
        'active',
        'birthday',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'city_id',
        'city_name',
        'provider_name',
        'provider_id',
        'provider_avatar',
        'provider_token',
        'provider_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->roles()->pluck('superadmin')->contains(true);
    }

    public function isManager()
    {
        return $this->roles()->pluck('manager')->contains(true);
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class);
    }

    public function unitsAll(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class)
            ->select('shortpath as name')
            ->orderBy('shortpath');
    }

    public function unitsClassified(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class)
            ->where('primary', true)
            ->select('shortpath as name')
            ->orderBy('shortpath');
    }

    public function unitsWorking()
    {
        return $this->belongsToMany(Unit::class)
            ->where('primary', false)
            ->select('shortpath as name')
            ->orderBy('shortpath');
    }

    public function scopeUnitsIds($query, $route = null)
    {
        if ($this->can('canManageNestedData', [User::class, $route])) {
            $units = $this->units->map->getDescendants()->flatten();
        } else {
            $units = $this->units->pluck('id');
        }

        return $units;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(UserDocument::class, 'owner');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(UserContact::class, 'owner');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(UserLocation::class, 'owner');
    }

    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'owner');
    }

    public function scopeSettings($query)
    {
        $settings = $query->join('user_settings', 'user_settings.owner', '=', 'users.id')
            ->join('user_setting_types', 'user_setting_types.id', '=', 'user_settings.type')
            ->select('user_settings.owner', 'user_settings.value', 'user_setting_types.name', 'user_setting_types.default_value')
            ->get();

        return UserSettingType::select('name', 'default_value')
            ->get()->map(function ($item) use ($settings) {
                $value = $settings->first(function ($setting) use ($item) {
                    return $setting->name == $item->name;
                });

                $item->value = $value ? $value->value : $item->default_value;

                return $item;
            })->pluck('value', 'name');
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
                ? __("Updated at") . " " . Carbon::make($value)->setTimeZone('America/Sao_Paulo')
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
