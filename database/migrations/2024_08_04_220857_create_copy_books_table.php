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
        Schema::create('Copy_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained("Book")->onDelete("cascade");
            $table->string("added_by");
            $table->enum("status", ["Available", "Reserved", "Borrowed"])->default("Available");
            $table->foreignId("from")->constrained('Origin_from');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Copy_books');
    }
};
