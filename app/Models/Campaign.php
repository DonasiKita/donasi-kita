<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'target_amount',
        'current_amount',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'target_amount' => 'integer',
        'current_amount' => 'integer',
        'is_active' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Donation::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return min(100, ($this->current_amount / $this->target_amount) * 100);
    }

    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    public function getFormattedCurrentAttribute()
    {
        return 'Rp ' . number_format($this->current_amount, 0, ',', '.');
    }
}
