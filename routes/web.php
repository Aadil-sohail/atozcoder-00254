<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EbayAccountController;
use App\Http\Controllers\EbaySyncController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\WarrantyController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/check-password', [ProfileController::class, 'checkPassword'])->name('profile.check-password'); //ajax check user password

     // Settings routes
    Route::patch('/company', [CompanyController::class, 'update'])->name('company.update');
    Route::patch('/smtp-setting', [SmtpSettingController::class, 'update'])->name('smtp-setting.update');

    // Roles & Permissions
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::match(['put', 'patch'], '/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/check-email', [UserController::class, 'checkEmail'])->name('users.check-email'); //ajax check user email
    Route::post('/users/check-username', [UserController::class, 'checkUsername'])->name('users.check-username'); //ajax check username

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::match(['put', 'patch'], '/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    // Warranties
    Route::get('/warranties', [WarrantyController::class, 'index'])->name('warranties.index');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::match(['put', 'patch'], '/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/check-name', [CategoryController::class, 'checkName'])->name('categories.check-name');

    // Out of stock products
    Route::get('/out-of-stock', [ProductController::class, 'outOfStock'])->name('out-of-stock.index');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/import/chunk', [ProductController::class, 'importChunk'])->name('products.import.chunk');
    Route::post('/products/import/finalize', [ProductController::class, 'importFinalize'])->name('products.import.finalize');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::match(['put', 'patch'], '/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Inventories
    Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
    Route::get('/inventories/category/{category}', [InventoryController::class, 'category'])->name('inventories.category');
    Route::get('/inventories/{product}', [InventoryController::class, 'show'])->name('inventories.show');

    // eBay stores & product sync
    Route::get('/ebay/stores', [EbayAccountController::class, 'index'])->name('ebay.index');
    Route::post('/ebay/connect', [EbayAccountController::class, 'connect'])->name('ebay.connect');
    Route::get('/ebay/callback', [EbayAccountController::class, 'callback'])->name('ebay.callback');
    Route::get('/ebay/stores/{ebayAccount}/setup', [EbayAccountController::class, 'setup'])->name('ebay.setup');
    Route::post('/ebay/stores/{ebayAccount}/setup', [EbayAccountController::class, 'saveSetup'])->name('ebay.setup.save');
    Route::post('/ebay/stores/{ebayAccount}/policies/default', [EbayAccountController::class, 'createDefaultPolicies'])->name('ebay.policies.default');
    Route::delete('/ebay/stores/{ebayAccount}', [EbayAccountController::class, 'destroy'])->name('ebay.destroy');
    Route::post('/ebay/sync', [EbaySyncController::class, 'sync'])->name('ebay.sync');
    Route::post('/ebay/sync-orders', [EbaySyncController::class, 'syncOrders'])->name('ebay.sync-orders');
    Route::post('/ebay/sync-returns', [EbaySyncController::class, 'syncReturns'])->name('ebay.sync-returns');
    Route::post('/ebay/sync-products', [EbaySyncController::class, 'syncProducts'])->name('ebay.sync-products');
    Route::delete('/ebay/listings/{ebayListing}', [EbaySyncController::class, 'destroy'])->name('ebay.listings.destroy');

    // Returns
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/returns/lookup-invoice', [ReturnController::class, 'lookupInvoice'])->name('returns.lookup-invoice'); //ajax invoice lookup
    Route::get('/returns/{saleReturn}', [ReturnController::class, 'show'])->name('returns.show');
    Route::delete('/returns/{saleReturn}', [ReturnController::class, 'destroy'])->name('returns.destroy');
});

require __DIR__.'/auth.php';
