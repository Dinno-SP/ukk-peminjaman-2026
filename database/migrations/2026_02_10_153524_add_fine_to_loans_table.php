<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Menambah kolom denda (default 0)
            $table->integer('fine')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
};
