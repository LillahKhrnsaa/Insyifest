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
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->title);
            }
        });
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_id');
    }
}