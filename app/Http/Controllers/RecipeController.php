<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class RecipeController extends Controller
{
    private const CATEGORIES = ['Breakfast', 'Lunch', 'Dinner', 'Appetizers', 'Dessert', 'Main Course', 'Side Dish', 'Snacks'];

    private const COURSE_CATEGORIES = self::CATEGORIES;

    private const METHOD_CATEGORIES = [
        'Baking',
        'Grilling',
        'Frying',
        'Boiling',
        'Steaming',
        'Roasting',
    ];

    private const RECIPE_CATEGORIES = [
        'Chicken Recipes',
        'Seafood Recipes',
        'Beef Recipes',
        'Pasta Recipes',
        'Dips & Spreads',
        'Dessert',
        'Pork Recipes',
        'Side Dish',
    ];

    private const MAX_VIDEO_SIZE_KILOBYTES = 204800;

    public function create(): View
    {
        return view('recipes.form', [
            'recipe' => new Recipe,
            'courseCategories' => self::COURSE_CATEGORIES,
            'methodCategories' => self::METHOD_CATEGORIES,
            'recipeCategories' => self::RECIPE_CATEGORIES,
        ]);
    }

    public function show(Recipe $recipe): View
    {
        $recipe->loadMissing('user');

        return view('recipes.show', [
            'recipe' => [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'author_id' => $recipe->user_id,
                'author' => $recipe->user->name,
                'category' => $recipe->category,
                'method' => $recipe->method,
                'recipe_type' => $recipe->recipe_type,
                'description' => $recipe->description,
                'prep_time_minutes' => $recipe->prep_time_minutes,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'image_url' => Storage::disk('public')->url($recipe->image_path),
                'video_url' => $recipe->video_path ? Storage::disk('public')->url($recipe->video_path) : null,
                'emoji' => null,
                'comment_action' => route('recipes.comments.store', $recipe),
            ],
            'comments' => $recipe->comments()->with('user')->latest()->get(),
        ]);
    }

    public function featured(string $slug): View
    {
        $recipes = $this->featuredRecipes();
        abort_unless(isset($recipes[$slug]), 404);

        return view('recipes.show', [
            'recipe' => $recipes[$slug] + [
                'image_url' => $recipes[$slug]['image_url'],
                'video_url' => null,
                'featured_slug' => $slug,
                'comment_action' => route('recipes.featured.comments.store', $slug),
            ],
            'comments' => RecipeComment::query()
                ->with('user')
                ->where('featured_recipe_slug', $slug)
                ->latest()
                ->get(),
        ]);
    }

    public function storeComment(Request $request, Recipe $recipe): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $recipe->comments()->create([
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        return to_route('recipes.show', $recipe)->with('status', 'Your comment was posted.');
    }

    public function storeFeaturedComment(Request $request, string $slug): RedirectResponse
    {
        $isFeatured = isset($this->featuredRecipes()[$slug]);
        $isCurated = collect(self::categoryCatalog())
            ->flatMap(fn (array $category): array => $category['recipes'])
            ->contains(fn (array $recipe): bool => Str::slug($recipe['name']) === $slug);
        abort_unless($isFeatured || $isCurated, 404);

        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user->recipeComments()->create([
            'featured_recipe_slug' => $slug,
            'body' => $data['body'],
        ]);

        return $isFeatured
            ? to_route('recipes.featured', $slug)->with('status', 'Your comment was posted.')
            : back()->with('status', 'Your comment was posted.');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403);

        $data = $this->validatedRecipeData($request, true);
        $data['image_path'] = $this->storeRecipePhoto($request);
        $data['video_path'] = $request->hasFile('video')
            ? $this->storeRecipeVideo($request)
            : null;

        $user->recipes()->create($data);

        return to_route('profile.show')->with('status', 'Your recipe was published.');
    }

    public function edit(Request $request, Recipe $recipe): View
    {
        $this->authorizeOwner($request, $recipe);

        return view('recipes.form', [
            'recipe' => $recipe,
            'courseCategories' => array_values(array_unique([
                ...self::COURSE_CATEGORIES,
                ...($recipe->category && ! in_array($recipe->category, self::COURSE_CATEGORIES, true)
                    ? [$recipe->category]
                    : []),
            ])),
            'methodCategories' => self::METHOD_CATEGORIES,
            'recipeCategories' => self::RECIPE_CATEGORIES,
        ]);
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $this->authorizeOwner($request, $recipe);
        $data = $this->validatedRecipeData($request, false);

        $oldImagePath = $recipe->image_path;
        $oldVideoPath = $recipe->video_path;
        $replacePhoto = $request->hasFile('photo');
        $replaceVideo = $request->hasFile('video');

        if ($replacePhoto) {
            $data['image_path'] = $this->storeRecipePhoto($request);
        }

        if ($replaceVideo) {
            $data['video_path'] = $this->storeRecipeVideo($request);
        }

        $recipe->update($data);

        if ($replacePhoto) {
            Storage::disk('public')->delete($oldImagePath);
        }

        if ($replaceVideo && $oldVideoPath !== null) {
            Storage::disk('public')->delete($oldVideoPath);
        }

        return to_route('profile.show')->with('status', 'Your recipe was updated.');
    }

    public function destroy(Request $request, Recipe $recipe): RedirectResponse
    {
        $this->authorizeOwner($request, $recipe);
        $imagePath = $recipe->image_path;
        $videoPath = $recipe->video_path;
        $recipe->delete();
        Storage::disk('public')->delete($imagePath);

        if ($videoPath !== null) {
            Storage::disk('public')->delete($videoPath);
        }

        return to_route('profile.show')->with('status', 'Your recipe was deleted.');
    }

    public function category(string $category): View
    {
        $categories = self::categoryCatalog();
        abort_unless(array_key_exists($category, $categories), 404);

        $user = auth()->user();
        $favoriteSlugs = $user instanceof User
            ? $user->recipeFavorites()->where('category', $category)->pluck('recipe_slug')->all()
            : [];

        return view('recipes.category', $categories[$category] + [
            'category_slug' => $category,
            'favorite_slugs' => $favoriteSlugs,
        ]);
    }

    public function categoryRecipe(string $category, string $recipeSlug): View
    {
        $categories = self::categoryCatalog();
        abort_unless(array_key_exists($category, $categories), 404);

        $recipe = collect($categories[$category]['recipes'])
            ->first(fn (array $recipe): bool => Str::slug($recipe['name']) === $recipeSlug);
        abort_unless($recipe !== null, 404);

        $user = auth()->user();
        $isFavorite = $user instanceof User && $user->recipeFavorites()
            ->where('category', $category)
            ->where('recipe_slug', $recipeSlug)
            ->exists();

        return view('recipes.show', [
            'recipe' => [
                'title' => $recipe['name'],
                'author' => 'KitchHug Kitchen',
                'category' => $categories[$category]['title'],
                'description' => $recipe['description'],
                'prep_time' => $recipe['time'],
                'prep_time_minutes' => null,
                'ingredients' => null,
                'instructions' => null,
                'image_url' => $recipe['image_url'],
                'video_url' => null,
                'emoji' => null,
                'comment_action' => route('recipes.featured.comments.store', $recipeSlug),
                'back_url' => auth()->check()
                    ? route('recipes.category', $category)
                    : route('recipes.public-category', $category),
                'is_curated' => true,
                'category_slug' => $category,
                'recipe_slug' => $recipeSlug,
                'is_favorite' => $isFavorite,
            ],
            'comments' => RecipeComment::query()
                ->with('user')
                ->where('featured_recipe_slug', $recipeSlug)
                ->latest()
                ->get(),
        ]);
    }

    public static function categoryCatalog(): array
    {
        return [
            'chicken' => [
                'title' => 'Chicken Recipes',
                'description' => 'From quick weeknight dinners to special-occasion favorites, these chicken dishes bring satisfying flavor to every table.',
                'recipes' => [
                    ['name' => 'Lemon Herb Roast Chicken', 'time' => '50 mins', 'rating' => '4.9', 'emoji' => '🍗', 'color' => 'from-orange-100 to-amber-50', 'description' => 'Golden roasted chicken with bright lemon, garlic, and garden herbs.', 'image_url' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Creamy Tuscan Chicken', 'time' => '35 mins', 'rating' => '4.8', 'emoji' => '🍲', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tender chicken in a silky tomato cream sauce with spinach.', 'image_url' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Crispy Chicken Tenders', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🍗', 'color' => 'from-amber-100 to-yellow-50', 'description' => 'Crunchy, seasoned chicken tenders with a quick honey mustard dip.', 'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'seafood' => [
                'title' => 'Seafood Recipes',
                'description' => 'Discover fresh seafood ideas, from quick shrimp dinners to comforting fish dishes packed with coastal flavor.',
                'recipes' => [
                    ['name' => 'Garlic Butter Shrimp', 'time' => '20 mins', 'rating' => '4.9', 'emoji' => '🍤', 'color' => 'from-orange-100 to-amber-50', 'description' => 'Juicy shrimp tossed in a quick garlic butter and lemon sauce.', 'image_url' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Honey Glazed Salmon', 'time' => '30 mins', 'rating' => '4.8', 'emoji' => '🐟', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Oven-baked salmon with a sweet-savory glaze and crisp edges.', 'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Crispy Fish Tacos', 'time' => '35 mins', 'rating' => '4.7', 'emoji' => '🌮', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Flaky fish, crunchy slaw, and tangy crema tucked into warm tortillas.', 'image_url' => 'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'beef' => [
                'title' => 'Beef Recipes',
                'description' => 'Find hearty beef recipes for easy family meals, slow-cooked comfort food, and everything in between.',
                'recipes' => [
                    ['name' => 'Slow-Cooked Beef Stew', 'time' => '2 hrs', 'rating' => '4.9', 'emoji' => '🥘', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Fork-tender beef simmered with vegetables in a rich savory broth.', 'image_url' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Weeknight Beef Stir-Fry', 'time' => '25 mins', 'rating' => '4.8', 'emoji' => '🥩', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Sizzling strips of beef with crisp vegetables and a ginger sauce.', 'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Classic Beef Burgers', 'time' => '30 mins', 'rating' => '4.7', 'emoji' => '🍔', 'color' => 'from-yellow-100 to-amber-50', 'description' => 'Juicy, well-seasoned patties ready for all your favorite toppings.', 'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'pasta' => [
                'title' => 'Pasta Recipes',
                'description' => 'Explore comforting pasta dishes, from creamy favorites to bright, fresh meals made for sharing.',
                'recipes' => [
                    ['name' => 'Garlic Cream Fettuccine', 'time' => '30 mins', 'rating' => '4.9', 'emoji' => '🍝', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Silky parmesan cream sauce with roasted garlic and fresh herbs.', 'image_url' => 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Roasted Tomato Penne', 'time' => '35 mins', 'rating' => '4.8', 'emoji' => '🍅', 'color' => 'from-orange-100 to-rose-50', 'description' => 'Sweet roasted tomatoes and basil folded through tender penne.', 'image_url' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Lemony Pesto Orzo', 'time' => '20 mins', 'rating' => '4.7', 'emoji' => '🍋', 'color' => 'from-lime-100 to-yellow-50', 'description' => 'Bright pesto orzo with lemon, parmesan, and toasted pine nuts.', 'image_url' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'dips-spreads' => [
                'title' => 'Dips & Spreads',
                'description' => 'Bring everyone together with easy dips and spreads for snacks, gatherings, and everyday meals.',
                'recipes' => [
                    ['name' => 'Creamy Hummus', 'time' => '10 mins', 'rating' => '4.9', 'emoji' => '🥣', 'color' => 'from-amber-100 to-lime-50', 'description' => 'Smooth chickpeas blended with tahini, lemon, and a hint of garlic.', 'image_url' => 'https://images.unsplash.com/photo-1577906096429-f73c2c312435?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Whipped Feta Dip', 'time' => '10 mins', 'rating' => '4.8', 'emoji' => '🧀', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tangy feta whipped until fluffy and finished with herbs and honey.', 'image_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Garden Guacamole', 'time' => '15 mins', 'rating' => '4.7', 'emoji' => '🥑', 'color' => 'from-emerald-100 to-lime-50', 'description' => 'Fresh avocado with lime, cilantro, and a little crunch from red onion.', 'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'dessert' => [
                'title' => 'Dessert',
                'description' => 'Make room for something sweet with simple, satisfying desserts for celebrations or a treat at home.',
                'recipes' => [
                    ['name' => 'Warm Apple Crumble', 'time' => '45 mins', 'rating' => '4.9', 'emoji' => '🥧', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Soft cinnamon apples under a golden, buttery oat topping.', 'image_url' => 'https://images.unsplash.com/photo-1562007908-17c67e878c88?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Chocolate Fudge Brownies', 'time' => '40 mins', 'rating' => '4.8', 'emoji' => '🍫', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Rich, fudgy brownies with a crackly top and plenty of chocolate.', 'image_url' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Berry Yogurt Parfaits', 'time' => '15 mins', 'rating' => '4.7', 'emoji' => '🍓', 'color' => 'from-pink-100 to-rose-50', 'description' => 'Layers of juicy berries, creamy yogurt, and crunchy granola.', 'image_url' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'pork' => [
                'title' => 'Pork Recipes',
                'description' => 'Enjoy savory pork recipes with comforting flavors, easy preparations, and ideas for any day of the week.',
                'recipes' => [
                    ['name' => 'Maple Glazed Pork Chops', 'time' => '30 mins', 'rating' => '4.9', 'emoji' => '🥩', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Pan-seared chops finished with a sweet maple and mustard glaze.', 'image_url' => 'https://images.unsplash.com/photo-1432139555190-58524dae6a55?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Slow Cooker Pulled Pork', 'time' => '6 hrs', 'rating' => '4.8', 'emoji' => '🍖', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tender, smoky pulled pork made for sandwiches and easy dinners.', 'image_url' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Ginger Pork Lettuce Cups', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🥬', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Savory ginger pork wrapped in crisp lettuce with a bright crunch.', 'image_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
            'side-dish' => [
                'title' => 'Side Dish',
                'description' => 'Round out your meal with simple sides, crisp vegetables, and flavorful dishes everyone can enjoy.',
                'recipes' => [
                    ['name' => 'Crispy Garlic Potatoes', 'time' => '40 mins', 'rating' => '4.9', 'emoji' => '🥔', 'color' => 'from-amber-100 to-yellow-50', 'description' => 'Golden oven-roasted potatoes tossed with garlic and rosemary.', 'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Fresh Garden Salad', 'time' => '10 mins', 'rating' => '4.8', 'emoji' => '🥗', 'color' => 'from-emerald-100 to-lime-50', 'description' => 'Crisp seasonal vegetables with a light homemade dressing.', 'image_url' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?auto=format&fit=crop&w=900&q=80'],
                    ['name' => 'Parmesan Roasted Broccoli', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🥦', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Tender broccoli with crisp edges, parmesan, and a squeeze of lemon.', 'image_url' => 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?auto=format&fit=crop&w=900&q=80'],
                ],
            ],
        ];

    }

    /**
     * @return array<string, array{title: string, category: string, description: string, prep_time_minutes: int, ingredients: string, instructions: string, emoji: string, image_url: string}>
     */
    private function featuredRecipes(): array
    {
        return [
            'crispy-chicken' => [
                'title' => 'Crispy Chicken',
                'category' => 'Main Course',
                'description' => 'Juicy chicken coated in a crisp, savory crust.',
                'prep_time_minutes' => 30,
                'ingredients' => "2 chicken breasts\n1 cup breadcrumbs\n1 egg, beaten\n1/2 cup flour\n1 teaspoon garlic powder\nSalt and pepper\n2 tablespoons cooking oil",
                'instructions' => "1. Season the chicken with salt, pepper, and garlic powder.\n2. Coat each piece in flour, dip in beaten egg, then cover with breadcrumbs.\n3. Heat the oil in a pan over medium heat.\n4. Cook the chicken for 5-7 minutes per side until golden and cooked through.\n5. Rest briefly before serving.",
                'emoji' => '🍗',
                'image_url' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80',
            ],
            'fluffy-pancakes' => [
                'title' => 'Fluffy Pancakes',
                'category' => 'Breakfast',
                'description' => 'Soft, golden pancakes made for a cozy breakfast.',
                'prep_time_minutes' => 15,
                'ingredients' => "1 cup all-purpose flour\n1 tablespoon sugar\n1 teaspoon baking powder\n1 egg\n3/4 cup milk\n2 tablespoons melted butter\nA pinch of salt",
                'instructions' => "1. Whisk together the flour, sugar, baking powder, and salt.\n2. In a separate bowl, whisk the egg, milk, and melted butter.\n3. Stir the wet ingredients into the dry ingredients until just combined.\n4. Pour small portions onto a warm, lightly buttered pan.\n5. Flip when bubbles appear and cook until golden on both sides.",
                'emoji' => '🥞',
                'image_url' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=900&q=80',
            ],
            'garlic-cream-pasta' => [
                'title' => 'Garlic Cream Pasta',
                'category' => 'Pasta',
                'description' => 'Tender pasta tossed in a creamy garlic and parmesan sauce.',
                'prep_time_minutes' => 25,
                'ingredients' => "250 g pasta\n2 tablespoons butter\n3 cloves garlic, minced\n1 cup cream\n1/2 cup grated parmesan\nSalt and black pepper\nFresh parsley",
                'instructions' => "1. Cook the pasta in salted water according to the package directions; reserve a little pasta water.\n2. Melt the butter in a pan and gently cook the garlic until fragrant.\n3. Stir in the cream and simmer for 2-3 minutes.\n4. Add the parmesan and stir until smooth.\n5. Toss with the drained pasta, loosening the sauce with pasta water if needed. Season and top with parsley.",
                'emoji' => '🍝',
                'image_url' => 'https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=900&q=80',
            ],
            'fresh-garden-salad' => [
                'title' => 'Fresh Garden Salad',
                'category' => 'Healthy',
                'description' => 'Crisp seasonal vegetables with a bright homemade dressing.',
                'prep_time_minutes' => 10,
                'ingredients' => "4 cups mixed lettuce\n1 cucumber, sliced\n1 cup cherry tomatoes, halved\n1/2 red onion, thinly sliced\n2 tablespoons olive oil\n1 tablespoon lemon juice\nSalt and black pepper",
                'instructions' => "1. Wash and dry the lettuce and vegetables.\n2. Combine the lettuce, cucumber, tomatoes, and red onion in a large bowl.\n3. Whisk together the olive oil, lemon juice, salt, and pepper.\n4. Drizzle the dressing over the salad and toss just before serving.",
                'emoji' => '🥗',
                'image_url' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?auto=format&fit=crop&w=900&q=80',
            ],
        ];
    }

    /**
     * @return array{title: string, category: string, method: string, recipe_type: string, description: string, ingredients: string, instructions: string, prep_time_minutes: int}
     */
    private function validatedRecipeData(Request $request, bool $requirePhoto): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', Rule::in(self::CATEGORIES)],
            'method' => ['required', 'string', Rule::in(self::METHOD_CATEGORIES)],
            'recipe_type' => ['required', 'string', Rule::in(self::RECIPE_CATEGORIES)],
            'description' => ['required', 'string', 'max:2000'],
            'ingredients' => ['required', 'string', 'max:10000'],
            'instructions' => ['required', 'string', 'max:20000'],
            'prep_time_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:'.self::MAX_VIDEO_SIZE_KILOBYTES],
            'photo' => [$requirePhoto ? 'required' : 'nullable', 'image', 'max:5120'],
        ]);
    }

    private function storeRecipePhoto(Request $request): string
    {
        $photo = $request->file('photo');

        abort_unless($photo instanceof UploadedFile, 422, 'A recipe photo is required.');

        $path = $photo->store('recipe-images', 'public');

        if ($path === false) {
            throw new RuntimeException('Unable to store the recipe photo.');
        }

        return $path;
    }

    private function storeRecipeVideo(Request $request): string
    {
        $video = $request->file('video');

        abort_unless($video instanceof UploadedFile, 422, 'The recipe video is invalid.');

        $path = $video->store('recipe-videos', 'public');

        if ($path === false) {
            throw new RuntimeException('Unable to store the recipe video.');
        }

        return $path;
    }

    private function authorizeOwner(Request $request, Recipe $recipe): void
    {
        $user = $request->user();

        abort_unless($user instanceof User && $recipe->user_id === $user->id, 404);
    }
}
