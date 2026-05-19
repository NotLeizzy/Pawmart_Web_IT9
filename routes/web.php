<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PetOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Customer Dashboard
    Route::get('/dashboard', function () {
        $userId = \Illuminate\Support\Facades\Auth::id();

        $myOrdersCount = \App\Models\Order::where('user_id', $userId)->count();
        $myPetOrdersCount = \App\Models\PetOrder::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();

        // Count of successfully adopted pets is same as pet orders
        $myPetsCount = $myPetOrdersCount;

        $recentOrders = \App\Models\Order::where('user_id', $userId)->latest()->take(5)->get();
        $featuredProducts = \App\Models\Product::with('images')->where('stock', '>', 0)->latest()->take(4)->get();
        $availablePets = \App\Models\Pet::where('status', 'available')->latest()->take(4)->get();

        return view('dashboard', compact(
            'myOrdersCount',
            'myPetOrdersCount',
            'myPetsCount',
            'recentOrders',
            'featuredProducts',
            'availablePets'
        ));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */
    Route::resource('products', ProductController::class)
        ->only(['index', 'show']);

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{productId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::patch('/cart/{productId}/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/products/add-to-cart', [ProductController::class, 'addToCart'])->name('products.addToCart');

    /*
    |--------------------------------------------------------------------------
    | PETS
    |--------------------------------------------------------------------------
    */
    Route::post('/pets/add-to-cart', [PetController::class, 'addToCart'])->name('pets.addToCart');
    Route::resource('pets', PetController::class);

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */
    Route::resource('orders', OrderController::class);

    /*
    |--------------------------------------------------------------------------
    | ORDER ITEMS
    |--------------------------------------------------------------------------
    */
    Route::resource('order-items', OrderItemController::class);

    /*
    |--------------------------------------------------------------------------
    | PET ORDERS
    |--------------------------------------------------------------------------
    */
    Route::resource('pet-orders', PetOrderController::class);

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
    Route::resource('payments', PaymentController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', function () {
            $totalProducts = \App\Models\Product::count();
            $totalOrders = \App\Models\Order::count();
            $totalPets = \App\Models\Pet::count();
            $lowStockItems = \App\Models\Product::where('stock', '<', 5)->count();
            $totalCategories = \App\Models\Category::count();
            $totalPayments = \App\Models\Payment::count();
            $totalPetOrders = \App\Models\PetOrder::count();
            $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();

            // Run Live DSA Timing Benchmarks for input sizes 50, 100, 200
            $dsaTimings = \App\Services\DsaBenchmarkService::runEvaluation();

            return view('admin.dashboard', compact(
                'totalProducts',
                'totalOrders',
                'totalPets',
                'lowStockItems',
                'totalCategories',
                'totalPayments',
                'totalPetOrders',
                'recentOrders',
                'dsaTimings'
            ));
        })->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | CATEGORY MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('categories', CategoryController::class);

        /*
        |--------------------------------------------------------------------------
        | PRODUCT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('products', ProductController::class);

        /*
        |--------------------------------------------------------------------------
        | STOCK MOVEMENTS
        |--------------------------------------------------------------------------
        */
        Route::get('/stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');

        Route::get('/stock-movements/{id}', [StockMovementController::class, 'show'])
            ->name('stock-movements.show');

        Route::post('/stock-movements/in', [StockMovementController::class, 'stockIn'])
            ->name('stock-movements.in');

        Route::post('/stock-movements/out', [StockMovementController::class, 'stockOut'])
            ->name('stock-movements.out');

        /*
        |--------------------------------------------------------------------------
        | ORDER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('orders', OrderController::class);
        Route::put('/admin/orders/{id}', [OrderController::class, 'update'])
            ->name('admin.orders.update');

        Route::put('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])
            ->name('orders.cancel');

        Route::put('/orders/{id}/confirm', [OrderController::class, 'confirmReceived'])
            ->name('orders.confirm');

        /*
        |--------------------------------------------------------------------------
        | PAYMENT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('payments', PaymentController::class);

        /*
        |--------------------------------------------------------------------------
        | PET MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('pets', PetController::class);

        /*
        |--------------------------------------------------------------------------
        | PET ORDER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('pet-orders', PetOrderController::class);
    });

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
