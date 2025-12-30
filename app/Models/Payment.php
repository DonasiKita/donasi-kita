<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'method',
        'amount',
        'status',
        'transaction_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'integer',
        'metadata' => 'array',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'success' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-info',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        return $texts[$this->status] ?? $this->status;
    }
}
