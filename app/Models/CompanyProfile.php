<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyProfile extends Model
{
    use LogsActivity;

    protected $table = 'company_profile';

    protected $fillable = [
        'name',
        'logo',
        'about',
        'vision',
        'mission',
        'email',
        'phone',
        'address',
        'social_media',
    ];

    protected $casts = [
        'social_media' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'name' => config('app.name', 'Company'),
        ]);
    }
}
