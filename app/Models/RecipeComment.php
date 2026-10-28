<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'recipe_id', 'featured_recipe_slug', 'parent_id', 'body'])]
class RecipeComment extends Model
{
    /**
     * @return BelongsTo<Recipe, $this>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<RecipeComment, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RecipeComment::class, 'parent_id');
    }

    /**
     * @return HasMany<RecipeComment, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(RecipeComment::class, 'parent_id')->with('user')->latest();
    }
}
