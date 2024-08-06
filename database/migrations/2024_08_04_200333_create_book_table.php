<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * title
     * author_id
     * ISBN
     * publisher
     * publication_date
     * genre_id
     * category_id
     */
    public function up(): void
    {
        Schema::create('Book', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            // $table->foreignId('author_id')->constrained('Author_Book')->onDelete("set null");
            $table->string('ISBN');
            $table->string('publisher');
            $table->date('publication_date');
            $table->string('cover_image_path')->unique();
            $table->integer("accession_number")->unique();
            // $table->foreignId('genre_id')->constrained('Genre')->onDelete("set null");
            $table->foreignId('category_id')->nullable()->constrained('Category')->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Book');
    }
};
