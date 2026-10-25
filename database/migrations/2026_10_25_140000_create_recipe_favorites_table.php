<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 50);
            $table->string('recipe_slug', 120);
            $table->timestamps();
            $table->unique(['user_id', 'category', 'recipe_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_favorites');
    }
};
