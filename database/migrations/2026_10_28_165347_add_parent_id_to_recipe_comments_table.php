<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipe_comments', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('id')
                ->constrained('recipe_comments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recipe_comments', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\RecipeComment::class, 'parent_id');
            $table->dropColumn('parent_id');
        });
    }
};
