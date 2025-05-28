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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika di-rollback, tambahkan kembali kolomnya
            // Sesuaikan tipe data dengan yang ada sebelumnya jika ingin rollback persis,
            // atau gunakan boolean jika itu yang seharusnya.
            // Mengingat aslinya string:
            // $table->string('is_admin')->default(false)->after('password');
            // Tapi jika ingin "memperbaiki" saat rollback:
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }
};
