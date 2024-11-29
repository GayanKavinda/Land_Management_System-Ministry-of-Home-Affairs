<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Estate extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'estates';

    protected $fillable = [
        'province',
        'district',
        'divisional_secretariat',
        'grama_niladari_division',
        'land_situated_village',
        'acquired_land_name',
        'acquired_land_extent',
        'total_extent_allotment_included',
        'claimant_name_and_address',
        'office_file_recorded',
        'land_acquired_purpose',
        'land_acquisition_certificate',
        'plan_availability',
        'plan_no_and_lot_no',
        'plan_image',
        'boundaries_of_land',
    ];

    protected static $logAttributes = [
        'province',
        'district',
        'divisional_secretariat',
        'grama_niladari_division',
        'land_situated_village',
        'acquired_land_name',
        'acquired_land_extent',
        'total_extent_allotment_included',
        'claimant_name_and_address',
        'office_file_recorded',
        'land_acquired_purpose',
        'land_acquisition_certificate',
        'plan_availability',
        'plan_no_and_lot_no',
        'plan_image',
        'boundaries_of_land',
    ];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "Estate was {$eventName}")
            ->logOnly(static::$logAttributes)
            ->logOnlyDirty(); // Only log attributes that have changed
    }
}
