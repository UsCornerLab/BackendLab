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
        Schema::create('supported_books', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('support_request_id')->constrained('book_support_requests');
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable(); 
            $table->string('publisher');
            $table->enum('delivery_status', ['Pending', 'Delivered'])->default('Pending');
            $table->integer('number_of_books');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supported_books');
    }
};
