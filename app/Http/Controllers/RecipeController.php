<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class RecipeController extends Controller
{
    private const CATEGORIES = ['Breakfast', 'Lunch', 'Dinner', 'Appetizers', 'Dessert', 'Main Course', 'Side Dish', 'Snacks'];

    public function create(): View
    {
        return view('recipes.form', [
            'recipe' => new Recipe,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function show(Recipe $recipe): View
    {
        return view('recipes.show', [
            'recipe' => [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'category' => $recipe->category,
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
                'image_url' => null,
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
        abort_unless(isset($this->featuredRecipes()[$slug]), 404);

        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user->recipeComments()->create([
            'featured_recipe_slug' => $slug,
            'body' => $data['body'],
        ]);

        return to_route('recipes.featured', $slug)->with('status', 'Your comment was posted.');
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
            'categories' => self::CATEGORIES,
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
        $categories = [
            'chicken' => [
                'title' => 'Chicken Recipes',
                'recipes' => [
                    ['name' => 'Lemon Herb Roast Chicken', 'time' => '50 mins', 'rating' => '4.9', 'emoji' => '🍗', 'color' => 'from-orange-100 to-amber-50', 'description' => 'Golden roasted chicken with bright lemon, garlic, and garden herbs.'],
                    ['name' => 'Creamy Tuscan Chicken', 'time' => '35 mins', 'rating' => '4.8', 'emoji' => '🍲', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tender chicken in a silky tomato cream sauce with spinach.'],
                    ['name' => 'Crispy Chicken Tenders', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🍗', 'color' => 'from-amber-100 to-yellow-50', 'description' => 'Crunchy, seasoned chicken tenders with a quick honey mustard dip.'],
                ],
            ],
            'seafood' => [
                'title' => 'Seafood Recipes',
                'recipes' => [
                    ['name' => 'Garlic Butter Shrimp', 'time' => '20 mins', 'rating' => '4.9', 'emoji' => '🍤', 'color' => 'from-orange-100 to-amber-50', 'description' => 'Juicy shrimp tossed in a quick garlic butter and lemon sauce.'],
                    ['name' => 'Honey Glazed Salmon', 'time' => '30 mins', 'rating' => '4.8', 'emoji' => '🐟', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Oven-baked salmon with a sweet-savory glaze and crisp edges.'],
                    ['name' => 'Crispy Fish Tacos', 'time' => '35 mins', 'rating' => '4.7', 'emoji' => '🌮', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Flaky fish, crunchy slaw, and tangy crema tucked into warm tortillas.'],
                ],
            ],
            'beef' => [
                'title' => 'Beef Recipes',
                'recipes' => [
                    ['name' => 'Slow-Cooked Beef Stew', 'time' => '2 hrs', 'rating' => '4.9', 'emoji' => '🥘', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Fork-tender beef simmered with vegetables in a rich savory broth.'],
                    ['name' => 'Weeknight Beef Stir-Fry', 'time' => '25 mins', 'rating' => '4.8', 'emoji' => '🥩', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Sizzling strips of beef with crisp vegetables and a ginger sauce.'],
                    ['name' => 'Classic Beef Burgers', 'time' => '30 mins', 'rating' => '4.7', 'emoji' => '🍔', 'color' => 'from-yellow-100 to-amber-50', 'description' => 'Juicy, well-seasoned patties ready for all your favorite toppings.'],
                ],
            ],
            'pasta' => [
                'title' => 'Pasta Recipes',
                'recipes' => [
                    ['name' => 'Garlic Cream Fettuccine', 'time' => '30 mins', 'rating' => '4.9', 'emoji' => '🍝', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Silky parmesan cream sauce with roasted garlic and fresh herbs.'],
                    ['name' => 'Roasted Tomato Penne', 'time' => '35 mins', 'rating' => '4.8', 'emoji' => '🍅', 'color' => 'from-orange-100 to-rose-50', 'description' => 'Sweet roasted tomatoes and basil folded through tender penne.'],
                    ['name' => 'Lemony Pesto Orzo', 'time' => '20 mins', 'rating' => '4.7', 'emoji' => '🍋', 'color' => 'from-lime-100 to-yellow-50', 'description' => 'Bright pesto orzo with lemon, parmesan, and toasted pine nuts.'],
                ],
            ],
            'dips-spreads' => [
                'title' => 'Dips & Spreads',
                'recipes' => [
                    ['name' => 'Creamy Hummus', 'time' => '10 mins', 'rating' => '4.9', 'emoji' => '🥣', 'color' => 'from-amber-100 to-lime-50', 'description' => 'Smooth chickpeas blended with tahini, lemon, and a hint of garlic.'],
                    ['name' => 'Whipped Feta Dip', 'time' => '10 mins', 'rating' => '4.8', 'emoji' => '🧀', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tangy feta whipped until fluffy and finished with herbs and honey.'],
                    ['name' => 'Garden Guacamole', 'time' => '15 mins', 'rating' => '4.7', 'emoji' => '🥑', 'color' => 'from-emerald-100 to-lime-50', 'description' => 'Fresh avocado with lime, cilantro, and a little crunch from red onion.'],
                ],
            ],
            'dessert' => [
                'title' => 'Dessert',
                'recipes' => [
                    ['name' => 'Warm Apple Crumble', 'time' => '45 mins', 'rating' => '4.9', 'emoji' => '🥧', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Soft cinnamon apples under a golden, buttery oat topping.'],
                    ['name' => 'Chocolate Fudge Brownies', 'time' => '40 mins', 'rating' => '4.8', 'emoji' => '🍫', 'color' => 'from-rose-100 to-amber-50', 'description' => 'Rich, fudgy brownies with a crackly top and plenty of chocolate.'],
                    ['name' => 'Berry Yogurt Parfaits', 'time' => '15 mins', 'rating' => '4.7', 'emoji' => '🍓', 'color' => 'from-pink-100 to-rose-50', 'description' => 'Layers of juicy berries, creamy yogurt, and crunchy granola.'],
                ],
            ],
            'pork' => [
                'title' => 'Pork Recipes',
                'recipes' => [
                    ['name' => 'Maple Glazed Pork Chops', 'time' => '30 mins', 'rating' => '4.9', 'emoji' => '🥩', 'color' => 'from-amber-100 to-orange-50', 'description' => 'Pan-seared chops finished with a sweet maple and mustard glaze.'],
                    ['name' => 'Slow Cooker Pulled Pork', 'time' => '6 hrs', 'rating' => '4.8', 'emoji' => '🍖', 'color' => 'from-rose-100 to-orange-50', 'description' => 'Tender, smoky pulled pork made for sandwiches and easy dinners.'],
                    ['name' => 'Ginger Pork Lettuce Cups', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🥬', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Savory ginger pork wrapped in crisp lettuce with a bright crunch.'],
                ],
            ],
            'side-dish' => [
                'title' => 'Side Dish',
                'recipes' => [
                    ['name' => 'Crispy Garlic Potatoes', 'time' => '40 mins', 'rating' => '4.9', 'emoji' => '🥔', 'color' => 'from-amber-100 to-yellow-50', 'description' => 'Golden oven-roasted potatoes tossed with garlic and rosemary.'],
                    ['name' => 'Fresh Garden Salad', 'time' => '10 mins', 'rating' => '4.8', 'emoji' => '🥗', 'color' => 'from-emerald-100 to-lime-50', 'description' => 'Crisp seasonal vegetables with a light homemade dressing.'],
                    ['name' => 'Parmesan Roasted Broccoli', 'time' => '25 mins', 'rating' => '4.7', 'emoji' => '🥦', 'color' => 'from-lime-100 to-emerald-50', 'description' => 'Tender broccoli with crisp edges, parmesan, and a squeeze of lemon.'],
                ],
            ],
        ];

        abort_unless(array_key_exists($category, $categories), 404);

        return view('recipes.category', $categories[$category]);
    }

    /**
     * @return array<string, array{title: string, category: string, description: string, prep_time_minutes: int, ingredients: string, instructions: string, emoji: string}>
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
            ],
            'fluffy-pancakes' => [
                'title' => 'Fluffy Pancakes',
                'category' => 'Breakfast',
                'description' => 'Soft, golden pancakes made for a cozy breakfast.',
                'prep_time_minutes' => 15,
                'ingredients' => "1 cup all-purpose flour\n1 tablespoon sugar\n1 teaspoon baking powder\n1 egg\n3/4 cup milk\n2 tablespoons melted butter\nA pinch of salt",
                'instructions' => "1. Whisk together the flour, sugar, baking powder, and salt.\n2. In a separate bowl, whisk the egg, milk, and melted butter.\n3. Stir the wet ingredients into the dry ingredients until just combined.\n4. Pour small portions onto a warm, lightly buttered pan.\n5. Flip when bubbles appear and cook until golden on both sides.",
                'emoji' => '🥞',
            ],
            'garlic-cream-pasta' => [
                'title' => 'Garlic Cream Pasta',
                'category' => 'Pasta',
                'description' => 'Tender pasta tossed in a creamy garlic and parmesan sauce.',
                'prep_time_minutes' => 25,
                'ingredients' => "250 g pasta\n2 tablespoons butter\n3 cloves garlic, minced\n1 cup cream\n1/2 cup grated parmesan\nSalt and black pepper\nFresh parsley",
                'instructions' => "1. Cook the pasta in salted water according to the package directions; reserve a little pasta water.\n2. Melt the butter in a pan and gently cook the garlic until fragrant.\n3. Stir in the cream and simmer for 2-3 minutes.\n4. Add the parmesan and stir until smooth.\n5. Toss with the drained pasta, loosening the sauce with pasta water if needed. Season and top with parsley.",
                'emoji' => '🍝',
            ],
            'fresh-garden-salad' => [
                'title' => 'Fresh Garden Salad',
                'category' => 'Healthy',
                'description' => 'Crisp seasonal vegetables with a bright homemade dressing.',
                'prep_time_minutes' => 10,
                'ingredients' => "4 cups mixed lettuce\n1 cucumber, sliced\n1 cup cherry tomatoes, halved\n1/2 red onion, thinly sliced\n2 tablespoons olive oil\n1 tablespoon lemon juice\nSalt and black pepper",
                'instructions' => "1. Wash and dry the lettuce and vegetables.\n2. Combine the lettuce, cucumber, tomatoes, and red onion in a large bowl.\n3. Whisk together the olive oil, lemon juice, salt, and pepper.\n4. Drizzle the dressing over the salad and toss just before serving.",
                'emoji' => '🥗',
            ],
        ];
    }

    /**
     * @return array{title: string, category: string, description: string, ingredients: string, instructions: string, prep_time_minutes: int}
     */
    private function validatedRecipeData(Request $request, bool $requirePhoto): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', Rule::in(self::CATEGORIES)],
            'description' => ['required', 'string', 'max:2000'],
            'ingredients' => ['required', 'string', 'max:10000'],
            'instructions' => ['required', 'string', 'max:20000'],
            'prep_time_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
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
