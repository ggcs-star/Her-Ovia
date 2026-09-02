<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\ProductInvoiceController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DeliverySettingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Admin\ReelController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PurchaseOrderController;  
use App\Http\Controllers\InventoryController;    
use Google\Client;
use App\Services\Amazon\AmazonInventoryService;

Route::get('/test-amazon-inventory', function () {

    $service = new AmazonInventoryService();

    return $service->testInventory('MJ-7009-WHT');

});
Route::get('/amazon-test', function () {

    $request = new \SellingPartnerApi\Seller\FBAInventoryV1\Requests\GetInventorySummaries(
        'Marketplace',
        env('SP_API_MARKETPLACE_ID'),
        [env('SP_API_MARKETPLACE_ID')],
        true
    );

    dd(
        $request->resolveEndpoint(),
        $request->defaultQuery()
    );
});
Route::get('/admin/instagram', function () {
    return view('admin.instagram.index');
})->middleware('auth')->name('admin.instagram');
Route::post('/instagram/disconnect', [InstagramController::class, 'disconnect'])
    ->middleware('auth')
    ->name('instagram.disconnect');
Route::middleware(['auth'])->group(function () {

    Route::get('/accounts/facebook/connect', [InstagramController::class, 'redirect'])
        ->name('facebook.connect');

    Route::get('/accounts/facebook/callback', [InstagramController::class, 'callback'])
        ->name('facebook.callback');

    Route::get('/accounts/instagram/reels', [InstagramController::class, 'reels'])
        ->name('instagram.reels');
});
Route::get('/test-auth', function () {
    return [
        'check' => Auth::check(),
        'id' => Auth::id(),
        'session' => session()->getId(),
    ];
});
// Callback auth ke bahar
Route::get('/accounts/facebook/callback', [InstagramController::class, 'callback'])
    ->name('facebook.callback');

// Auth routes
Route::middleware(['auth'])->group(function () {

    Route::get('/accounts/facebook/connect', [InstagramController::class, 'redirect'])
        ->name('facebook.connect');

    Route::get('/accounts/instagram/reels', [InstagramController::class, 'reels'])
        ->name('instagram.reels');
});

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:3,1');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])
        ->middleware('throttle:3,5');

    Route::get('/reset-password', [ResetPasswordController::class, 'index'])->name('password.verify');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->middleware('throttle:5,5');
});

Route::get('/verify-email', [EmailVerificationController::class, 'index'])->name('verify.email');
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])
    ->middleware('throttle:5,5');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'verified.email', 'log.login.activity'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


// ADMIN ROUTES - Prefix: /admin

Route::middleware(['auth', 'verified.email', 'log.login.activity', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


  Route::get('/inventory/dashboard', [InventoryController::class, 'dashboard'])
            ->name('inventory.dashboard');

        Route::get('/inventory/data', [InventoryController::class, 'getData'])
            ->name('inventory.data');

        // Route::get('/inventory/{variant}/details', [InventoryController::class, 'details'])
        //     ->name('inventory.details');
    Route::get('/inventory/search-suggestions', [InventoryController::class, 'searchSuggestions'])->name('inventory.search');

        // Route::get('/inventory/{variantId}/details', [InventoryController::class, 'details'])
        //     ->name('inventory.details');
Route::get('/inventory/details/{product}', [InventoryController::class, 'details'])
    ->name('inventory.details');
        Route::resource('reels', ReelController::class);
        Route::post('/reels/{reel}/comment', [ReelController::class, 'addComment'])
            ->name('reels.comment');
        Route::delete('/reels/comment/{comment}', [ReelController::class, 'deleteComment'])
            ->name('reels.comment.delete');
        Route::post('/reels/{reel}/share', [ReelController::class, 'addShare'])
            ->name('reels.share');
        Route::get('/reels/{reel}/stats', [ReelController::class, 'stats'])
            ->name('reels.stats');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [DashboardController::class, 'getChartData'])->name('dashboard.admin.data');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers/create', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('/suppliers/{supplier}/details', [SupplierController::class, 'details'])->name('suppliers.details');
        Route::post('/suppliers/bulk-delete', [SupplierController::class, 'bulkDelete'])->name('suppliers.bulk-delete');
        Route::get(
            '/delivery-settings',
            [DeliverySettingController::class, 'index']
        )->name('delivery-settings.index');

        Route::get(
            '/delivery-settings/create',
            [DeliverySettingController::class, 'create']
        )->name('delivery-settings.create');

        Route::post(
            '/delivery-settings',
            [DeliverySettingController::class, 'store']
        )->name('delivery-settings.store');

        Route::post(
            '/delivery-settings/bulk-delete',
            [DeliverySettingController::class, 'bulkDelete']
        )->name('delivery-settings.bulk-delete');

        Route::get(
            '/delivery-settings/{deliverySetting}/edit',
            [DeliverySettingController::class, 'edit']
        )->name('delivery-settings.edit');

        Route::put(
            '/delivery-settings/{deliverySetting}',
            [DeliverySettingController::class, 'update']
        )->name('delivery-settings.update');

        Route::delete(
            '/delivery-settings/{deliverySetting}',
            [DeliverySettingController::class, 'destroy']
        )->name('delivery-settings.destroy');

        Route::get(
            '/delivery-settings/{deliverySetting}',
            [DeliverySettingController::class, 'show']
        )->name('delivery-settings.show');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/categories/{category}/details', [CategoryController::class, 'details'])->name('categories.details');
        Route::post('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
        Route::get('/get-subcategories', [CategoryController::class, 'getSubcategories'])->name('admin.get.subcategories');
        Route::get('/banners', [BannerController::class, 'index'])
            ->name('banners.index');

        Route::get('/banners/create', [BannerController::class, 'create'])
            ->name('banners.create');

        Route::post('/banners', [BannerController::class, 'store'])
            ->name('banners.store');

        Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])
            ->name('banners.edit');

        Route::put('/banners/{banner}', [BannerController::class, 'update'])
            ->name('banners.update');
        Route::get('/banners/{banner}', [BannerController::class, 'show'])
            ->name('banners.show');
        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])
            ->name('banners.destroy');
        Route::get(
            '/products/{product}/invoice',
            [ProductInvoiceController::class, 'view']
        )->name('products.invoice.view');

        Route::get(
            '/products/{product}/invoice/download',
            [ProductInvoiceController::class, 'download']
        )->name('products.invoice.download');


        Route::get(
            '/products/{product}/invoice/pdf',
            [ProductInvoiceController::class, 'pdf']
        )->name('products.invoice.pdf');

        Route::get(
            '/products/{product}/invoice/image',
            [ProductInvoiceController::class, 'image']
        )->name('products.invoice.image');
        Route::delete(
            'products/{product}/image/{index}',
            [ProductController::class, 'deleteImage']
        )->name('products.image.delete');
        Route::resource('purchase-orders', PurchaseOrderController::class);

        Route::prefix('purchase-orders')
            ->name('purchase-orders.')
            ->group(function () {

                Route::get(
                    'supplier/{supplier}',
                    [PurchaseOrderController::class, 'getSupplier']
                )->name('supplier');

                Route::get(
                    'product/{product}/variants',
                    [PurchaseOrderController::class, 'getProductVariants']
                )->name('variants');

            });
        Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
        Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
        Route::get('/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
        Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
        Route::get('/warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
        Route::post('/warehouses/bulk-delete', [WarehouseController::class, 'bulkDelete'])->name('warehouses.bulk-delete');

        // Variants master (add / edit / delete)
        Route::resource('variants', VariantController::class);

        // Variant values
        Route::post(
            'variants/{variant}/values',
            [VariantController::class, 'storeValue']
        )->name('variants.values.store');

        Route::delete(
            'variant-values/{value}',
            [VariantController::class, 'destroyValue']
        )->name('variants.values.destroy');

        Route::put(
            'variant-values/{value}',
            [VariantController::class, 'updateValue']
        )->name('variants.values.update');

        Route::get(
            'variants/{variant}/values',
            [VariantController::class, 'getValues']
        )->name('admin.variants.values.list');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');

        Route::get('/products/list', [ProductController::class, 'list'])->name('products.list');
        Route::get('/products/push', [ProductController::class, 'push'])->name('products.push');
        Route::post('/products/push', [ProductController::class, 'pushStore'])->name('products.push.store');
        Route::delete('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
Route::get('/products/search', [ProductController::class, 'searchProducts'])
    ->name('products.search');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/platform/{platform}/products', [ProductController::class, 'platformProducts'])
            ->name('platform.products');
        // Bulk delete banks
        Route::post('/banks/bulk-delete', [BankController::class, 'bulkDelete'])->name('banks.bulk-delete');

        Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
        Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
        Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
        Route::get('/banks/{bank}/edit', [BankController::class, 'edit'])->name('banks.edit');
        Route::put('/banks/{bank}', [BankController::class, 'update'])->name('banks.update');
        Route::delete('/banks/{bank}', [BankController::class, 'destroy'])->name('banks.destroy');
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/push', [CouponController::class, 'create'])
            ->name('coupons.push');
        Route::post('/coupons/bulk-delete', [CouponController::class, 'bulkDelete'])->name('coupons.bulk-delete');
        // routes/admin.php
        Route::get('coupons/{coupon}', [CouponController::class, 'show'])
            ->name('coupons.show');
Route::get('/coupons/subcategories/{category}', [CouponController::class, 'getSubCategories'])
    ->name('coupons.subcategories');

Route::get('/coupons/products/{subcategory}', [CouponController::class, 'getProducts'])
    ->name('coupons.products');
        Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::get('/organizations', [OrganizationController::class, 'index'])
            ->name('organizations.index');

        Route::get('/organizations/create', [OrganizationController::class, 'create'])
            ->name('organizations.create');
        Route::get(
            '/organizations/{organization}',
            [OrganizationController::class, 'show']
        )->name('organizations.show');

        Route::post('/organizations', [OrganizationController::class, 'store'])
            ->name('organizations.store');

        Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])
            ->name('organizations.edit');

        Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])
            ->name('organizations.update');

        Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])
            ->name('organizations.destroy');

        Route::post('/organizations/bulk-delete', [OrganizationController::class, 'bulkDelete'])
            ->name('organizations.bulk-delete');




// Route::get('/notification-settings', [OrganizationController::class, 'notificationSettings'])
//     ->name('notification-settings.index');



    // Notification Settings
Route::get('/notification-settings', [NotificationSettingController::class, 'index'])
    ->name('notification-settings.index');

Route::get('/notification-settings/create', [NotificationSettingController::class, 'create'])
    ->name('notification-settings.create');

Route::post('/notification-settings', [NotificationSettingController::class, 'store'])
    ->name('notification-settings.store');

Route::get('/notification-settings/{notificationSetting}/edit', [NotificationSettingController::class, 'edit'])
    ->name('notification-settings.edit');

Route::put('/notification-settings/{notificationSetting}', [NotificationSettingController::class, 'update'])
    ->name('notification-settings.update');

Route::delete('/notification-settings/{notificationSetting}', [NotificationSettingController::class, 'destroy'])
    ->name('notification-settings.destroy');
            
        Route::get(
            '/app-settings',
            [AppSettingController::class, 'index']
        )->name('app-settings.index');

        Route::get(
            '/app-settings/create',
            [AppSettingController::class, 'create']
        )->name('app-settings.create');

        Route::post(
            '/app-settings',
            [AppSettingController::class, 'store']
        )->name('app-settings.store');

        Route::get(
            '/app-settings/{appSetting}',
            [AppSettingController::class, 'show']
        )->name('app-settings.show');

        Route::get(
            '/app-settings/{appSetting}/edit',
            [AppSettingController::class, 'edit']
        )->name('app-settings.edit');

        Route::put(
            '/app-settings/{appSetting}',
            [AppSettingController::class, 'update']
        )->name('app-settings.update');

        Route::delete(
            '/app-settings/{appSetting}',
            [AppSettingController::class, 'destroy']
        )->name('app-settings.destroy');
        // Customers CRUD
        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('/customers/create', [CustomerController::class, 'create'])
            ->name('customers.create');

        Route::post('/customers', [CustomerController::class, 'store'])
            ->name('customers.store');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');

        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('customers.edit');

        Route::put('/customers/{customer}', [CustomerController::class, 'update'])
            ->name('customers.update');

        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])
            ->name('customers.destroy');

        Route::post('/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])
            ->name('customers.bulk-delete');
        // Invoice
        Route::get('/invoices/create', [InvoiceController::class, 'create'])
            ->name('invoices.create');

        Route::post('/invoices', [InvoiceController::class, 'store'])
            ->name('invoices.store');

        Route::get('/invoices', [InvoiceController::class, 'index'])
            ->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
            ->name('invoices.show');

        // Print / Download invoice
        Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])
            ->name('invoices.print');
        Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])
            ->name('invoices.edit');
        Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])
            ->name('invoices.update');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])
            ->name('invoices.destroy');
        Route::get(
            '/products/{product}/variants',
            [InvoiceController::class, 'productVariants']
        )->name('products.variants');

        // Customer ajax
        Route::post(
            '/customers/ajax-store',
            [CustomerController::class, 'ajaxStore']
        )->name('customers.ajax.store');



        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{id}/confirm', [OrderController::class, 'confirm'])
    ->name('orders.confirm');
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus'); // ✅ FIXED NAME
    
        Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])
            ->name('orders.invoice');

        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');
        Route::get('/stock-management', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock-settings', [StockSettingController::class, 'index'])->name('stock.settings');
        Route::post('/stock-settings', [StockSettingController::class, 'update'])->name('stock.settings.update');

     
        Route::post('/notification/save-token', [NotificationController::class, 'saveToken'])
            ->name('notification.save-token');

        Route::post('/notification/send-all', [NotificationController::class, 'sendToAll'])
            ->name('notification.send-all');

        Route::post('/notification/send-user', [NotificationController::class, 'sendToUser'])
            ->name('notification.send-user');

        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notification.index');
Route::get('/test-notification', [NotificationController::class, 'testNotification'])
    ->name('notification.test');
        Route::post('/notification/read/{id}', [NotificationController::class, 'markAsRead'])
            ->name('notification.read');

        Route::delete('/notification/{id}', [NotificationController::class, 'destroy'])
            ->name('notification.destroy');


    });
Route::get('/taxes', [\App\Http\Controllers\TaxController::class, 'index'])
    ->name('taxes.index');

Route::get('/taxes/create', [\App\Http\Controllers\TaxController::class, 'create'])
    ->name('taxes.create');

Route::post('/taxes', [\App\Http\Controllers\TaxController::class, 'store'])
    ->name('taxes.store');

Route::get('/taxes/{tax}/edit', [\App\Http\Controllers\TaxController::class, 'edit'])
    ->name('taxes.edit');

Route::put('/taxes/{tax}', [\App\Http\Controllers\TaxController::class, 'update'])
    ->name('taxes.update');

Route::delete('/taxes/{tax}', [\App\Http\Controllers\TaxController::class, 'destroy'])
    ->name('taxes.destroy');

// USER ROUTES - Prefix: /users

Route::middleware(['auth', 'verified.email', 'log.login.activity', 'role:user'])
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    });