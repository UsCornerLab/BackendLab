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
        Schema::create('Shelf_book', function (Blueprint $table) {
            $table->id();
            $table->string("shelf_name");
            $table->integer("shelf_number");
            $table->foreignId("book_id")->constrained("Book")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Shelf_book');
    }
};
