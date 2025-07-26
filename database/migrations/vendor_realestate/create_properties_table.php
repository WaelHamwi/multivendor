<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id'); // Maps to main.vendors.id
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('location');
            $table->enum('property_type', ['apartment', 'villa', 'land', 'commercial']);
            $table->enum('status', ['available', 'sold', 'rented'])->default('available');
            $table->unsignedBigInteger('created_by'); // Reference to user_id (main.users.id)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
