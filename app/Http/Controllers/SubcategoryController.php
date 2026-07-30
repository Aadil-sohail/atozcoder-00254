<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view subcategories')->only('index');
        $this->middleware('permission:create subcategories')->only('store');
        $this->middleware('permission:edit subcategories')->only('update');
        $this->middleware('permission:delete subcategories')->only('destroy');
        $this->middleware('permission:create subcategories|edit subcategories')->only('checkName');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subcategories = Subcategory::with('category')
            ->where(['status' => '1', 'close' => '1'])
            ->latest('id')
            ->get();

        $categories = Category::where(['status' => '1', 'close' => '1'])->orderBy('name')->get();

        return view('subcategories.index', compact('subcategories', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('subcategories.index')->with('status', 'Sub category added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $subcategory->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategories.index')->with('status', 'Sub category updated successfully.');
    }

    public function checkName(Request $request): JsonResponse
    {
        $name       = trim((string) $request->input('name', ''));
        $categoryId = (int) $request->input('category_id', 0);
        $excludeId  = (int) $request->input('exclude_id', 0);

        $taken = Subcategory::where('name', $name)
            ->where('category_id', $categoryId)
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
     * remains reserved and cannot be reused by a new sub category.
     */
    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->update([
            'status' => '0',
            'close' => '0',
        ]);

        return redirect()->route('subcategories.index')->with('status', 'Sub category deleted successfully.');
    }
}
