<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('vendor__db')->create('car_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->string('file_path');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::connection('vendor__db')->dropIfExists('car_images');
    }
};
