<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Tambahkan kolom backer_count jika belum ada
            if (!Schema::hasColumn('campaigns', 'backer_count')) {
                $table->integer('backer_count')->default(0)->after('current_amount');
            }

            // Tambahkan kolom deadline jika belum ada (sekalian jaga-jaga)
            if (!Schema::hasColumn('campaigns', 'deadline')) {
                $table->dateTime('deadline')->nullable()->after('backer_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['backer_count', 'deadline']);
        });
    }
};
