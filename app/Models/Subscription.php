<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'subscription_plan';

    protected static $cacheKey = 'allowed_plans';
    protected static function booted()
    {
        static::saved(function ($plan) {
            self::clearCache();
        });

        static::deleted(function ($plan) {
            self::clearCache();
        });
    }

    public static function clearCache()
    {
        Cache::forget(self::$cacheKey);
    }
}
