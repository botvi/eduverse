<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kuesioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis', ['pre_test', 'post_test']);
            $table->json('jawaban'); // array [1=>4, 2=>5, 3=>3, ...]
            $table->decimal('skor_total', 5, 2)->nullable();
            $table->timestamps();

            // Setiap user hanya boleh isi pre_test sekali dan post_test sekali
            $table->unique(['user_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioners');
    }
};
