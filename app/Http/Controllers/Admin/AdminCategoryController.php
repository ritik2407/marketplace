<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    /**
     * List all categories and their subcategories.
     */
    public function index(): View
    {
        $categories = Category::withCount('listings')
            ->with(['subcategories' => fn ($q) => $q->withCount('listings')])
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? 'folder',
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? (Category::max('order') + 1),
        ]);

        return back()->with('success', "Category '{$validated['name']}' created successfully.");
    }

    /**
     * Store a new subcategory.
     */
    public function storeSubcategory(Request $request, int $categoryId): RedirectResponse
    {
        $category = Category::findOrFail($categoryId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Subcategory::create([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "Subcategory '{$validated['name']}' added to {$category->name}.");
    }

    /**
     * Delete a category.
     */
    public function destroy(int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Category '{$name}' deleted successfully.");
    }

    /**
     * Delete a subcategory.
     */
    public function destroySubcategory(int $id): RedirectResponse
    {
        $subcategory = Subcategory::findOrFail($id);
        $name = $subcategory->name;
        $subcategory->delete();

        return back()->with('success', "Subcategory '{$name}' deleted.");
    }
}
