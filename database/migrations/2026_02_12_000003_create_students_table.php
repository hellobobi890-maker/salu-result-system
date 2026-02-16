<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained('years')->cascadeOnDelete();
            $table->string('name');
            $table->string('roll_no');
            $table->string('reference_no');
            $table->string('qr_token')->unique();
            $table->timestamps();

            $table->unique(['year_id', 'roll_no']);
            $table->unique(['year_id', 'reference_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
