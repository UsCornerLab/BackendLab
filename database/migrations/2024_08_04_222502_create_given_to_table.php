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
        Schema::create('Given_to', function (Blueprint $table) {
            $table->id();
            $table->foreignId("copy_id")->constrained("Copy_books")->onDelete("cascade");
            $table->foreignId("borrowed_by")->constrained("Origin_from")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Given_to');
    }
};
