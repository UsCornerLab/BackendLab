<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * role_type -> enum
     */
    public function up(): void
    {
        Schema::create('Role', function (Blueprint $table) {
            $table->id();
            $table->enum('role_type', ['admin', 'librarian', 'user'])->default('user');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Role');
    }
};
