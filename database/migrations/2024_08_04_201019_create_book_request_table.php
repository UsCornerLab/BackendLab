<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * user_id
     * tile
     * author
     * request_letter
     * status
     */
    public function up(): void
    {
        Schema::create('Book_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('User')->onDelete("cascade");
            $table->string('title');
            $table->string('author');
            $table->text('request_letter');
            $table->enum('status',['Pending', 'Approved', 'Rejected', 'Delivered'])->default("Pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Book_request');
    }
};
