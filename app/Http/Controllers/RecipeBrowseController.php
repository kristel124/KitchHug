<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RecipeBrowseController extends Controller
{
    private const METHODS = [
        'Baking',
        'Grilling',
        'Frying',
        'Boiling',
        'Steaming',
        'Roasting',
    ];

    private const COURSES = [
        'Breakfast',
        'Lunch',
        'Dinner',
        'Appetizers',
        'Dessert',
        'Main Course',
        'Side Dish',
        'Snacks',
    ];

    public function byMethod(string $method): View
    {
        $label = $this->resolveCategory($method, self::METHODS);

        return $this->results('Recipes made by '.$label, Recipe::query()
            ->whereRaw('LOWER(method) = ?', [Str::lower($label)]));
    }

    public function byCourse(string $course): View
    {
        $label = $this->resolveCategory($course, self::COURSES);

        return $this->results($label.' Recipes', Recipe::query()
            ->whereRaw('LOWER(category) = ?', [Str::lower($label)]));
    }

    private function resolveCategory(string $slug, array $categories): string
    {
        foreach ($categories as $category) {
            if (Str::slug($category) === $slug) {
                return $category;
            }
        }

        abort(404);
    }

    private function results(string $title, Builder $query): View
    {
        return view('recipes.community-index', [
            'title' => $title,
            'recipes' => $query->withReactionSummary(Auth::id())->with('user')->latest()->paginate(12)->withQueryString(),
        ]);
    }
}
