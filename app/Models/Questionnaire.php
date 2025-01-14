<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
