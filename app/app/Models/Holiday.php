<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'active',
        'day_off',
        'authority',
        'country_id',
        'country_code',
        'state_id',
        'state_code',
        'city_id',
        'city_name',
        'easter',
        'date_start',
        'date_end',
        'sum_difference',
        'difference_start_days',
        'difference_start_time',
        'difference_end_days',
        'difference_end_time',
        'starts_at',
        'ends_at',
    ];

    protected function start(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date_format(date_create($value), "d/m/y H:i"),
        );
    }

    protected function end(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date_format(date_create($value), "d/m/y H:i"),
        );
    }

    public function scopeGetDates($query, $year = null): void
    {
        $year = $year ?? date("Y");

        $query->selectRaw("
        CASE
            WHEN holidays.easter = true AND holidays.operator = '-' THEN Easter(" . $year . ") - holidays.difference_start::interval
            WHEN holidays.easter = true AND holidays.operator = '+' THEN Easter(" . $year . ") + holidays.difference_start::interval
            ELSE TO_TIMESTAMP(" . $year . " || '-' || holidays.month || '-' || holidays.day || ' ' || holidays.start_time, 'YYYY-MM-DD HH24:MI:SS')
        END start
    ")
            ->selectRaw("
        CASE
            WHEN holidays.easter = true AND holidays.operator = '-' THEN Easter(" . $year . ") - holidays.difference_end::interval
            WHEN holidays.easter = true AND holidays.operator = '+' THEN Easter(" . $year . ") + holidays.difference_end::interval
            ELSE TO_TIMESTAMP(" . $year . " || '-' || holidays.month || '-' || holidays.day || ' ' || holidays.end_time, 'YYYY-MM-DD HH24:MI:SS')
        END end
    ");
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
