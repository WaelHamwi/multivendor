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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Link vendor to user
            $table->string('database_name')->unique();
            $table->enum('status', ['pending', 'approved', 'banned'])->default('pending');
            $table->enum('subscription', ['free', 'pro', 'enterprise'])->default('free');
            $table->string('department')->nullable();
            $table->timestamps();
        });
    }
};
