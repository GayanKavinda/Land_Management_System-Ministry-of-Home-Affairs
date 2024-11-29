<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NonEstate extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'non_estates';

    protected $fillable = [
        'province',
        'district',
        'divisional_secretariat',
        'grama_niladari_division',
        'estate_name',
        'plan_no',
        'land_extent',
        'building_available',
        'building_name',
        'government_land',
        'reason',
        // Add more fields as needed
    ];

    protected static $logAttributes = [
        'province',
        'district',
        'divisional_secretariat',
        'grama_niladari_division',
        'estate_name',
        'plan_no',
        'land_extent',
        'building_available',
        'building_name',
        'government_land',
        'reason',
        // Add more fields as needed
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "Non-Estate was {$eventName}")
            ->logOnly(static::$logAttributes)
            ->logOnlyDirty(); // Only log attributes that have changed
    }
}
