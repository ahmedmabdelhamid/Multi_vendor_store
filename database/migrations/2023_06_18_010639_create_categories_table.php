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
        Schema::create('categories', function (Blueprint $table) {
            // id BIGINT UNSIGNED AUTO INCREMENT PRIMARY
            $table->id();
            $table->foreignId('parent_id')
            ->nullable()
            ->constrained('categories' , 'id')
            ->nullOnDelete();
            $table->string('name'); // VARCHAR(255)
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'archived'])->default('active');
            // 2 columns: created_at and updated_at (timestamp)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
