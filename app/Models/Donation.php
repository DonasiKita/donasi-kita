<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'donor_name',
        'donor_email',
        'amount',
        'note',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_snap_token',
        'payment_data',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'payment_data' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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
            'expired' => 'badge-secondary',
        ];

        return $badges[$this->payment_status] ?? 'badge-secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
        ];

        return $texts[$this->payment_status] ?? $this->payment_status;
    }
}
