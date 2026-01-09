<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Tambahkan ini untuk generate Slug

class Campaign extends Model
{
    use HasFactory;

    // 1. UPDATE FILLABLE (PENTING)
    // Saya menambahkan 'deadline' dan 'backer_count' agar bisa disimpan controller
    protected $fillable = [
        'title',
        'slug',           // URL cantik (seo friendly)
        'description',
        'target_amount',
        'current_amount',
        'backer_count',   // Jumlah donatur
        'image_url',
        'deadline',       // Batas waktu
        'is_active',
    ];

    // 2. UPDATE CASTS
    // Agar 'deadline' otomatis jadi objek Carbon (bisa diformat tanggalnya)
    protected $casts = [
        'target_amount' => 'integer',
        'current_amount' => 'integer',
        'backer_count' => 'integer',
        'is_active' => 'boolean',
        'deadline' => 'datetime', // <--- PENTING: Agar bisa pakai $campaign->deadline->format()
    ];

    /**
     * Boot function untuk otomatisasi
     */
    protected static function boot()
    {
        parent::boot();

        // Otomatis membuat SLUG saat kampanye dibuat
        // Contoh: Judul "Bantu Anak Yatim" -> Slug "bantu-anak-yatim"
        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title) . '-' . time();
            }
        });
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // Hitung persentase
    public function getProgressPercentage()
    {
        if ($this->target_amount == 0) return 0;
        $percentage = ($this->current_amount / $this->target_amount) * 100;
        return min(100, round($percentage)); // Dibulatkan tanpa desimal agar rapi di progress bar
    }

    // Accessor: $campaign->progress_percentage
    public function getProgressPercentageAttribute()
    {
        return $this->getProgressPercentage();
    }

    // Accessor: Format Rupiah Target
    public function getFormattedTargetAttribute()
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    // Accessor: Format Rupiah Terkumpul
    public function getFormattedCurrentAttribute()
    {
        return 'Rp ' . number_format($this->current_amount, 0, ',', '.');
    }

    // 3. TAMBAHAN: Hitung Sisa Hari Secara Otomatis
    // Bisa dipanggil di view dengan: {{ $campaign->days_left }}
    public function getDaysLeftAttribute()
    {
        if (!$this->deadline) return 0;

        // Hitung selisih hari dari sekarang sampai deadline
        $days = now()->diffInDays($this->deadline, false); // false agar bisa negatif jika lewat

        return $days > 0 ? $days : 0; // Jika sudah lewat, return 0
    }

    // Accessor untuk Image URL agar aman jika null
    public function getImageUrlAttribute($value)
    {
        // Jika value ada isinya, kembalikan value tersebut
        // Jika kosong, kembalikan placeholder default
        return $value ? asset($value) : 'https://via.placeholder.com/800x400?text=No+Image';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
