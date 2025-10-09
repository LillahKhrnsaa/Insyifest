<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormEksternal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug',
        'fields',
        'status',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    // Generate slug otomatis dari title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($form) {
            // Auto slug dari title kalau kosong
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->title);
            }

            // Auto generate name untuk setiap field kalau belum ada
            if (!empty($form->fields) && is_array($form->fields)) {
                $form->fields = collect($form->fields)->map(function ($field) {
                    if (empty($field['name']) && !empty($field['label'])) {
                        $field['name'] = Str::snake(
                            Str::lower($field['label'])
                        );
                    }
                    return $field;
                })->toArray();
            }
        });

        static::updating(function ($form) {
            // Sama juga saat update
            if (!empty($form->fields) && is_array($form->fields)) {
                $form->fields = collect($form->fields)->map(function ($field) {
                    if (empty($field['name']) && !empty($field['label'])) {
                        $field['name'] = Str::snake(
                            Str::lower($field['label'])
                        );
                    }
                    return $field;
                })->toArray();
            }
        });
    }


    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_id');
    }

    protected static function normalizeFields($fields)
    {
        if (empty($fields) || !is_array($fields)) {
            return $fields;
        }

        return collect($fields)->map(function ($field) {
            if (empty($field['name']) && !empty($field['label'])) {
                $baseName = Str::snake(
                    Str::lower($field['label'])
                );
            } else {
                $baseName = $field['name'];
            }

            // Tipe yang bisa kirim array
            $multipleTypes = ['checkbox', 'select_multiple'];

            if (isset($field['type']) && in_array($field['type'], $multipleTypes)) {
                $field['name'] = rtrim($baseName, '[]') . '[]';
            } else {
                $field['name'] = rtrim($baseName, '[]');
            }

            return $field;
        })->toArray();
    }

}