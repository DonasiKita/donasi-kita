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
        'midtrans_snap_token',
        'midtrans_transaction_id',
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

    // Scope untuk donasi sukses
    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'success');
    }

    // Scope untuk donasi pending
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}
