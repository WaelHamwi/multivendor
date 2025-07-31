<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('vendor__db')->create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->decimal('price', 12, 2);
            $table->integer('mileage')->nullable();
            $table->string('color')->nullable();
            $table->string('status')->default('available');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('vendor__db')->dropIfExists('cars');
    }
};
