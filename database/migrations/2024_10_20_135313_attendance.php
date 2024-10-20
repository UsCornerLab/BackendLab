<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("User");
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->time('time_in')->default(DB::raw('CURRENT_TIME'));
            $table->time('time_out')->nullable();
            $table->enum('status', ['Present', 'Absent', 'Late'])->default('Present');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Attendance');
    }
};
