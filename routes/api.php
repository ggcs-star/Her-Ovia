    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\Users\ProductController;
    use App\Http\Controllers\Api\Users\CategoryController;
    use App\Http\Controllers\Api\Users\AuthController;
    use App\Http\Controllers\Api\Users\CartController;
    use App\Http\Controllers\Api\Users\AddressController;
    use App\Http\Controllers\Api\Users\ProfileController;
    use App\Http\Controllers\Api\Users\CouponController;
    use App\Http\Controllers\Api\Users\CheckoutController;
    use App\Http\Controllers\Api\Users\OrderController;
    use App\Http\Controllers\Api\Users\BannerController;
    use App\Http\Controllers\Api\Users\UserCategoryController;
    use App\Http\Controllers\Api\Users\AppSettingController;
    use App\Http\Controllers\Api\LikeController;
    use App\Http\Controllers\Api\Users\OrganizationController;
    use App\Http\Controllers\Api\CommentController;
    use App\Http\Controllers\InstagramController;
    use App\Http\Controllers\Api\Users\PageImpressionController;
    use App\Http\Controllers\Api\Users\ShippingController;
    Route::post('/shipping/check', [ShippingController::class, 'check'])
    ->middleware('throttle:30,1');

    Route::post('/page-impression', [PageImpressionController::class, 'store']);
    Route::get('/instagram/reels', [InstagramController::class, 'reels']);
    Route::post('/like', [LikeController::class, 'store']);
    Route::get('/like-count/{link_id}', [LikeController::class, 'count']);
    Route::post('/dislike', [LikeController::class, 'dislike']);

    Route::get('/is-liked/{link_id}', [LikeController::class, 'isLiked']);

    Route::post('/comments', [CommentController::class, 'store']);
    Route::get('/comments/{link_id}', [CommentController::class, 'index']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::get('/comment-count/{link_id}', [CommentController::class, 'count']);
    Route::post('/create-order',[OrderController::class,'createOrder']);

        Route::get('/my-orders',[OrderController::class,'orders']);

        Route::get('/order/{id}',[OrderController::class,'orderDetails']);
    use App\Http\Controllers\Api\Users\ReelController;
    Route::post('/razorpay/webhook', [CheckoutController::class,'razorpayWebhook']);

    Route::middleware('auth:sanctum')->get('/orders/latest', [OrderController::class,'latest']);


    Route::get('/reels', [ReelController::class, 'index']);
    Route::get('/reels/{id}', [ReelController::class, 'show']);
    Route::get('/reels/{id}/comments', [ReelController::class, 'comments']);
    Route::post('/reels/{id}/comment', [ReelController::class, 'addComment']);

    Route::post('/reels/{id}/view', [ReelController::class, 'increaseViews']);
    Route::post('/reels/{id}/like', [ReelController::class, 'like']);
    Route::post('/reels/{id}/share', [ReelController::class, 'share']);
    Route::get('/test', function () {
        return response()->json([
            'status' => true,
            'message' => 'API working from mobile'
        ]);
    });
    Route::get('/mail-test', function () {
        \Mail::raw('Test Email from Laravel', function ($message) {
            $message->to('your_other_email@gmail.com')
                    ->subject('Test Email');
        });

        return 'Mail sent';
    });
    Route::any('/debug-url', function (Request $request) {
        return response()->json([
            'full_url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'data' => $request->all(),
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "api" middleware group. Make something great!
    |
    */

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::prefix('user')->group(function () {

        Route::post('/register', [AuthController::class, 'register'])
            ->middleware('throttle:5,1');

        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1');

        Route::post('/verify-email-otp', [AuthController::class, 'verifyEmailOtp'])
            ->middleware('throttle:5,1');

        Route::post('/resend-email-otp', [AuthController::class, 'resendEmailOtp'])
            ->middleware('throttle:3,1');

                Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
            ->middleware('throttle:3,1');

        Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp'])
            ->middleware('throttle:5,1');

        Route::post('/reset-password', [AuthController::class, 'resetPassword'])
            ->middleware('throttle:3,1');
    });
    Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/apply', [CouponController::class, 'apply']);
            Route::post('/remove', [CouponController::class, 'remove']);
            
    
        });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/me', function (Request $request) {
            return response()->json($request->user());
        });

        Route::post('/cart/add', [CartController::class, 'add']);
        Route::get('/cart', [CartController::class, 'index']);
        Route::put('/cart/update/{cart_item}', [CartController::class, 'update']);
        Route::delete('/cart/remove/{cart_item}', [CartController::class, 'remove']);
        
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
        Route::get('/orders/{id}/track', [OrderController::class, 'track']);
        Route::get('/checkout/summary', [CheckoutController::class, 'summary']);
        Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder']);
        Route::get('/user/addresses', [AddressController::class, 'index']);
        Route::post('/user/addresses', [AddressController::class, 'store']);
        Route::get('/user/addresses/{id}', [AddressController::class, 'show']);
        Route::put('/user/addresses/{id}', [AddressController::class, 'update']);
        Route::delete('/user/addresses/{id}', [AddressController::class, 'destroy']);
        Route::put('/user/addresses/{id}/set-default', [AddressController::class, 'setDefault']);
        
        Route::get('/user/profile', [ProfileController::class, 'show']);
        Route::put('/user/profile', [ProfileController::class, 'update']);
        Route::delete('/user/profile/image', [ProfileController::class, 'removeImage']);
        Route::post('/checkout/razorpay/create-order', [CheckoutController::class, 'createRazorpayOrder']);
        Route::post('/checkout/razorpay/verify', [CheckoutController::class, 'verifyRazorpayPayment']);
        Route::get('/categories/order', [UserCategoryController::class,'index']);

        Route::post('/categories/order', [UserCategoryController::class,'save']);
    });


    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category_id}', [CategoryController::class, 'show']);
    Route::get(
        '/categories/{category}/products',
        [CategoryController::class, 'products']
    );


    Route::get('/organization/footer-details', [OrganizationController::class, 'footerDetails']);
    Route::get('/products/top-selling', [ProductController::class, 'topSelling']);
    Route::get('/best-sellers', [ProductController::class, 'bestSeller']);
    Route::get('/products/suggestions', [ProductController::class, 'searchSuggestions'])
        ->middleware('throttle:30,1');
    Route::get('/products/search', [ProductController::class, 'search'])
        ->middleware('throttle:30,1');
    Route::get('/search', [ProductController::class, 'unifiedSearch']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/id/{product_id}', [ProductController::class, 'showById']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/search/redirect', [ProductController::class, 'searchRedirect']);

    Route::get('/app-settings', [AppSettingController::class, 'index']);
    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index']);     
        Route::get('/{id}', [BannerController::class, 'show']);    
        Route::get('/page/{page}', [BannerController::class, 'getByPage']);
        Route::get('/position/{position}', [BannerController::class, 'getByPosition']);
    });
