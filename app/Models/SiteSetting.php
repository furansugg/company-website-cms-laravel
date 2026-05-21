<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'site_name',
        'site_description',
        'logo',
        'favicon',
        'primary_color',
        'default_meta_title',
        'default_meta_description',
        'default_og_image',
        'robots_txt',
        'analytics_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['site_name', 'primary_color'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function current(): self
    {
        return Cache::rememberForever('site_settings', function () {
            return static::firstOrCreate([], [
                'site_name' => config('app.name', 'Company Website'),
            ]);
        });
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
    }
}
