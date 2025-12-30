<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        Campaign::create([
            'title' => 'Bantuan Korban Banjir Jakarta',
            'slug' => 'bantuan-korban-banjir-jakarta',
            'description' => 'Bantuan untuk warga yang terdampak banjir di wilayah Jakarta. Dana akan digunakan untuk sembako, obat-obatan, dan kebutuhan darurat lainnya.',
            'target_amount' => 100000000,
            'current_amount' => 35000000,
            'image_url' => 'https://images.unsplash.com/photo-1589652717521-10c0d092dea9?auto=format&fit=crop&w=600',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Pendidikan Anak Yatim Piatu',
            'slug' => 'pendidikan-anak-yatim-piatu',
            'description' => 'Bantuan biaya pendidikan untuk anak yatim piatu di seluruh Indonesia. Meliputi biaya sekolah, seragam, buku, dan peralatan belajar.',
            'target_amount' => 50000000,
            'current_amount' => 12500000,
            'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=600',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Bantuan Medis untuk Lansia',
            'slug' => 'bantuan-medis-untuk-lansia',
            'description' => 'Penyediaan layanan kesehatan dan obat-obatan untuk lansia kurang mampu di daerah terpencil.',
            'target_amount' => 75000000,
            'current_amount' => 18000000,
            'image_url' => 'https://images.unsplash.com/photo-1516549655669-df6654e435f6?auto=format&fit=crop&w=600',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Beasiswa Mahasiswa Berprestasi',
            'slug' => 'beasiswa-mahasiswa-berprestasi',
            'description' => 'Program beasiswa untuk mahasiswa berprestasi dari keluarga kurang mampu.',
            'target_amount' => 80000000,
            'current_amount' => 25000000,
            'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=600',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Pembangunan Masjid Al-Ikhlas',
            'slug' => 'pembangunan-masjid-al-ikhlas',
            'description' => 'Pembangunan masjid untuk masyarakat Desa Sukamaju yang belum memiliki tempat ibadah.',
            'target_amount' => 200000000,
            'current_amount' => 85000000,
            'image_url' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=600',
            'is_active' => true,
        ]);
    }
}
