<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('status');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->enum('jenis_ujian', ['UTS', 'UAS', 'Lainnya'])->default('Lainnya')->after('jam_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai', 'jenis_ujian']);
        });
    }
};
