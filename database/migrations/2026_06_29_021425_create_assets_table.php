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
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('nama_aset');
        $table->string('serial_number')->unique();
        
        // Relasi Foreign Key ke data master Anda
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
        
        $table->enum('status', ['Bagus', 'Rusak', 'Perbaikan'])->default('Bagus');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
