<?php

namespace App\Notifications;

use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RecipeActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $activity,
        private readonly User $actor,
        private readonly ?Recipe $recipe = null,
        private readonly ?RecipeComment $comment = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $actor = [
            'id' => $this->actor->id,
            'name' => $this->actor->name,
            'profile_photo_path' => $this->actor->profile_photo_path,
        ];

        return match ($this->activity) {
            'new_recipe' => [
                'activity' => $this->activity,
                'message' => $this->actor->name.' shared a new recipe: '.$this->recipe->title,
                'actor' => $actor,
                'recipe_id' => $this->recipe->id,
                'url' => route('recipes.show', $this->recipe),
            ],
            'recipe_comment' => [
                'activity' => $this->activity,
                'message' => $this->actor->name.' commented on your recipe: '.$this->recipe->title,
                'actor' => $actor,
                'recipe_id' => $this->recipe->id,
                'comment_id' => $this->comment->id,
                'url' => route('recipes.show', $this->recipe).'#comments',
            ],
            'new_follower' => [
                'activity' => $this->activity,
                'message' => $this->actor->name.' started following you.',
                'actor' => $actor,
                'url' => route('users.show', $this->actor),
            ],
            'recipe_deleted' => [
                'activity' => $this->activity,
                'message' => 'Your recipe "'.$this->recipe->title.'" was removed for violating our community guidelines.',
                'actor' => $actor,
                'url' => route('notifications.index'),
            ],
            'comment_deleted' => [
                'activity' => $this->activity,
                'message' => 'Your comment on "'.$this->recipe->title.'" was removed for violating our community guidelines.',
                'actor' => $actor,
                'url' => route('notifications.index'),
            ],
            default => throw new \LogicException('Unsupported recipe activity notification.'),
        };
    }
}
