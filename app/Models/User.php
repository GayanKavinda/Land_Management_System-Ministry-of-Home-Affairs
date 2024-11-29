<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


use App\Models\UserRequest;


use Spatie\Activitylog\LogOptions;


class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    use \Spatie\Activitylog\Traits\LogsActivity;

    use CanResetPassword;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static $logAttributes = ['name', 'other_attribute'];


    public function getActivitylogOptions(): LogOptions
    {
        // Customize and return the LogOptions instance here
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "User was {$eventName}")
            // ->logName('user_activity_log') // Use logName instead of useLog
            ->logOnly(['name', 'email'])
            ->logExcept(['password', 'secret']);
    }


    
    public function userRequest()
    {
        return $this->hasOne(UserRequest::class);
    }

}
