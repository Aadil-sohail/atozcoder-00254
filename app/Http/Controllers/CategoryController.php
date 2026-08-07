<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view categories')->only('index');
        $this->middleware('permission:create categories')->only('store');
        $this->middleware('permission:edit categories')->only('update');
        $this->middleware('permission:delete categories')->only('destroy');
        $this->middleware('permission:create categories|edit categories')->only('checkName');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Fetched in full: searching, sorting and paging are handled client
        // side by DataTables, the same as the other lookup tables.
        $categories = Category::where(['status' => '1', 'close' => '1'])->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->name,
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('categories.index')->with('status', 'Category added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('status', 'Category updated successfully.');
    }

    public function checkName(Request $request): JsonResponse
    {
        $name      = trim((string) $request->input('name', ''));
        $excludeId = (int) $request->input('exclude_id', 0);

        $taken = Category::where('name', $name)
            ->where('status', '1')
            ->where('close', '1')
            ->when($excludeId > 0, function ($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->exists();

        return response()->json(['taken' => $taken]);
    }

    /**
     * Disable the specified resource instead of deleting it, so its name
     * remains reserved and cannot be reused by a new category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->update([
            'status' => '0',
            'close' => '0',
        ]);

        return redirect()->route('categories.index')->with('status', 'Category deleted successfully.');
    }
}
