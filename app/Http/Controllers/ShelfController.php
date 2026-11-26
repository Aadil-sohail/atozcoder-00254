<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Models\Product;
use App\Models\Rack;
use App\Models\Shelf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShelfController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view shelves')->only('index', 'show');
        $this->middleware('permission:create shelves')->only('store');
        $this->middleware('permission:edit shelves')->only('update', 'reorder');
        $this->middleware('permission:delete shelves')->only('destroy');
        $this->middleware('permission:create shelves|edit shelves')->only('checkName');
        $this->middleware('permission:remove shelf products')->only('removeProduct');
    }

    public function removeProduct(Shelf $shelf, Product $product): RedirectResponse
    {
        if ((int) $product->shelf_id !== (int) $shelf->id) {
            return redirect()->route('shelves.show', $shelf)->with('error', 'That product is not on this shelf.');
        }

        $ids = $product->productConnection
            ? $product->productConnection->items()->pluck('product_id')->push($product->id)->unique()
            : collect([$product->id]);

        Product::whereIn('id', $ids)->where('shelf_id', $shelf->id)->update(['shelf_id' => null]);

        return redirect()->route('shelves.show', $shelf)->with('status', "\"{$product->name}\" removed from this shelf.");
    }

    /**
     * Ordered by rack, then by the order set by drag and drop, so the listing
     * is already grouped by rack before DataTables takes over. Shelves of a
     * disabled rack are left out.
     */
    public function index(Request $request): View
    {
        $shelves = Shelf::choices()->loadCount(['products' => fn ($q) => Shelf::stockedProducts($q)]);

        $racks = Rack::where(['status' => '1', 'close' => '1'])->orderBy('name')->get();
        $selectedRack = $racks->firstWhere('id', (int) $request->query('rack'));
        $emptyRacks = $racks->whereNotIn('id', $shelves->pluck('rack_id'))->values();

        return view('shelves.index', compact('shelves', 'racks', 'selectedRack', 'emptyRacks'));
    }

    /**
     * One shelf and every product kept on it.
     */
    public function show(Shelf $shelf): View
    {
        $shelf->load('rack');

        $products = Shelf::stockedProducts($shelf->products()->getQuery())
            ->with(['category', 'ebayListings.ebayAccount', 'productConnection.masterProduct', 'productConnection.items.product.ebayListings', 'productConnection.items.stores'])
            ->orderBy('name')
            ->get();

        return view('shelves.show', compact('shelf', 'products'));
    }

    public function store(StoreShelfRequest $request): RedirectResponse
    {
        Shelf::create([
            'name' => $request->name,
            'rack_id' => $request->rack_id,
            'sort_order' => $this->nextPosition((int) $request->rack_id),
            'inserted_by' => auth()->user()->name,
        ]);

        return redirect()->route('shelves.index')->with('status', 'Shelf added successfully.');
    }

    public function update(UpdateShelfRequest $request, Shelf $shelf): RedirectResponse
    {
        $rackId = (int) $request->rack_id;

        $shelf->update([
            'name' => $request->name,
            'rack_id' => $rackId,
            // Moved to another rack: it joins that rack at the bottom, rather
            // than landing at whatever position it had in the old one.
            'sort_order' => (int) $shelf->rack_id === $rackId ? $shelf->sort_order : $this->nextPosition($rackId),
        ]);

        return redirect()->route('shelves.index')->with('status', 'Shelf updated successfully.');
    }

    /**
     * Save a rack's shelves in the order they were dragged into.
     *
     * The page sends every shelf of the rack, top to bottom. If that is not
     * exactly the rack's shelves (someone added, moved or removed one in the
     * meantime), nothing is saved and the page is told to reload, rather than
     * numbering a list that no longer matches.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'rack_id' => ['required', 'integer'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $ids = array_map('intval', $data['ids']);

        $current = Shelf::where('rack_id', $data['rack_id'])
            ->where(['status' => '1', 'close' => '1'])
            ->pluck('id')
            ->all();

        if (count($ids) !== count($current) || array_diff($ids, $current) !== []) {
            return response()->json([
                'message' => 'The shelves of this rack changed since the page was loaded. Reload the page and try again.',
            ], 409);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $position => $id) {
                Shelf::whereKey($id)->update(['sort_order' => $position + 1]);
            }
        });

        return response()->json(['message' => 'Shelf order saved.']);
    }

    /**
     * The position after the last shelf of a rack.
     */
    private function nextPosition(int $rackId): int
    {
        return (int) Shelf::where('rack_id', $rackId)->max('sort_order') + 1;
    }

    public function checkName(Request $request): JsonResponse
    {
        $name = trim((string) $request->input('name', ''));
        $rackId = (int) $request->input('rack_id', 0);
        $excludeId = (int) $request->input('exclude_id', 0);

        $taken = Shelf::where('name', $name)
            ->where('rack_id', $rackId)
            ->where('status', '1')
            ->where('close', '1')
            ->when($excludeId > 0, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();

        return response()->json(['taken' => $taken]);
    }

    /**
     * Disable rather than delete, so the name stays reserved in its rack. A
     * shelf that still holds products is left alone: they would point at a
     * shelf nobody can see or pick any more.
     */
    public function destroy(Shelf $shelf): RedirectResponse
    {
        $products = $shelf->products()->where(['status' => '1', 'close' => '1'])->count();

        if ($products > 0) {
            return redirect()->route('shelves.index')->with(
                'error',
                "Shelf \"{$shelf->name}\" still holds {$products} ".str('product')->plural($products).'. Move them to another shelf first.'
            );
        }

        $shelf->update([
            'status' => '0',
            'close' => '0',
        ]);

        return redirect()->route('shelves.index')->with('status', 'Shelf deleted successfully.');
    }
}
