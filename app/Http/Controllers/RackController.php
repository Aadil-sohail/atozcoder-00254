<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRackRequest;
use App\Http\Requests\UpdateRackRequest;
use App\Models\Rack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Racks are managed from the Shelves page: added from its "Add Rack" button,
 * renamed and deleted from each rack's heading. There is no page of their own.
 */
class RackController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create racks')->only('store');
        $this->middleware('permission:edit racks')->only('update');
        $this->middleware('permission:delete racks')->only('destroy');
        $this->middleware('permission:create racks|edit racks')->only('checkName');
    }

    /**
     * The old Racks page: everything lives on the Shelves page now.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('shelves.index');
    }

    public function store(StoreRackRequest $request): RedirectResponse
    {
        $rack = Rack::create([
            'name' => $request->name,
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('shelves.index')
            ->with('status', "Rack \"{$rack->name}\" added. Use \"Add Shelf\" to give it shelves.");
    }

    public function update(UpdateRackRequest $request, Rack $rack): RedirectResponse
    {
        $rack->update([
            'name' => $request->name,
        ]);

        return redirect()->route('shelves.index')->with('status', 'Rack renamed successfully.');
    }

    public function checkName(Request $request): JsonResponse
    {
        $name = trim((string) $request->input('name', ''));
        $excludeId = (int) $request->input('exclude_id', 0);

        $taken = Rack::where('name', $name)
            ->where('status', '1')
            ->where('close', '1')
            ->when($excludeId > 0, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        return response()->json(['taken' => $taken]);
    }

    /**
     * Disable the rack and its shelves rather than delete them, so their names
     * stay reserved. Refused while any of its shelves still holds products:
     * those would be left on a shelf nobody can see or pick any more.
     */
    public function destroy(Rack $rack): RedirectResponse
    {
        $shelves = $rack->activeShelves()
            ->withCount(['products' => fn ($q) => $q->where(['status' => '1', 'close' => '1'])])
            ->get();

        $products = (int) $shelves->sum('products_count');

        if ($products > 0) {
            return redirect()->route('shelves.index')->with(
                'error',
                "Rack \"{$rack->name}\" still has {$products} ".str('product')->plural($products).' on its shelves. Move them to another shelf first.'
            );
        }

        DB::transaction(function () use ($rack) {
            $rack->activeShelves()->update(['status' => '0', 'close' => '0']);
            $rack->update(['status' => '0', 'close' => '0']);
        });

        $count = $shelves->count();

        return redirect()->route('shelves.index')->with(
            'status',
            $count > 0
                ? "Rack \"{$rack->name}\" and its {$count} ".str('shelf')->plural($count).' deleted.'
                : "Rack \"{$rack->name}\" deleted."
        );
    }
}
