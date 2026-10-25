<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'category', 'description', 'ingredients', 'instructions', 'prep_time_minutes', 'image_path', 'video_path'])]
class Recipe extends Model
{
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<RecipeComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(RecipeComment::class);
    }
}
