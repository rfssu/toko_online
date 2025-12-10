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
        Schema::create('file', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mime', 100)->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('parent_table', 100)->nullable();
            $table->string('parent_field', 100)->nullable();
            $table->text('keterangan')->nullable()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file');
    }
};
