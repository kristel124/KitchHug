<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipe_favorites', function (Blueprint $table): void {
            $table->string('category', 50)->nullable()->change();
            $table->string('recipe_slug', 120)->nullable()->change();
            $table->foreignId('recipe_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'recipe_id']);
        });
    }

    public function down(): void
    {
        Schema::table('recipe_favorites', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'recipe_id']);
            $table->dropConstrainedForeignId('recipe_id');
            $table->string('category', 50)->nullable(false)->change();
            $table->string('recipe_slug', 120)->nullable(false)->change();
        });
    }
};
