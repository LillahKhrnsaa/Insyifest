<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Support\Str;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'display_name',
        'guard_name',
        'description',
    ];

    protected static function generateDisplayName(string $name): string
    {
        // ubah create.role => Create Role
        return Str::headline(str_replace('.', ' ', $name));
    }

    protected static function generateDescription(string $name): string
    {
        // ubah create.role => Akses Create Role
        return 'Akses ' . self::generateDisplayName($name);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->display_name = $model->display_name ?? self::generateDisplayName($model->name);
            $model->description  = $model->description  ?? self::generateDescription($model->name);
        });

        static::updating(function ($model) {
            if (empty($model->display_name)) {
                $model->display_name = self::generateDisplayName($model->name);
            }
            if (empty($model->description)) {
                $model->description = self::generateDescription($model->name);
            }
        });
    }
}
