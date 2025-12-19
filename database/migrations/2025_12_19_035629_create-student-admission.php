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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();

            // Student details
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('class_applied');

            // Parent / Guardian details
            $table->string('parent_name');
            $table->string('parent_contact');

            // Additional info
            $table->text('address');
            $table->string('interest')->nullable();

            // Admin-related fields
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
