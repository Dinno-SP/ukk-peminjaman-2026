<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->string('phone')->nullable()->after('email');   <-- HAPUS atau KOMENTAR baris ini
            // $table->text('address')->nullable()->after('phone');   <-- HAPUS atau KOMENTAR baris ini (jika address juga sudah ada)

            // Sisakan yang belum ada saja (Kelas & NIS)
            $table->string('class')->nullable()->after('address'); 
            $table->string('nis')->nullable()->after('class');     
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
