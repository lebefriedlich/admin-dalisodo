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
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id_media')->primary();
            $table->uuid('id_berita')->nullable(); // Ubah ke UUID
            $table->uuid('id_potensi')->nullable(); // Ubah ke UUID
            $table->string('file_id')->unique()->nullable();
            $table->string('youtube_id')->nullable();
            $table->enum('tipe_media', ['Gambar', 'Youtube']);
            $table->timestamps();

            // Tambahkan foreign key constraint dengan tipe UUID
            $table->foreign('id_berita')->references('id_berita')->on('berita_desa')->onDelete('cascade');
            $table->foreign('id_potensi')->references('id_potensi')->on('potensi_desa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
