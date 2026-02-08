<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yg minjam
            $table->foreignId('tool_id')->constrained()->onDelete('cascade'); // Alat apa
            $table->date('loan_date'); // Tanggal pinjam
            $table->date('return_date'); // Rencana tanggal kembali
            $table->date('actual_return_date')->nullable(); // Tanggal asli kembali (diisi saat dikembalikan)
            // Status peminjaman 
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
