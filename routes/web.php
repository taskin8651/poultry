<?php
use App\Http\Controllers\Custom\ShopController;
use App\Http\Controllers\Custom\CartController;
use App\Http\Controllers\Custom\CheckoutController;
use App\Http\Controllers\Custom\OrderController;


Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

   Route::resource('categories', 'CategoryController');
    Route::resource('tags', 'TagController');
    Route::resource('orders', 'OrderController');
    Route::resource('heroes', 'HeroController');
    Route::resource('abouts', 'AboutController');
    Route::resource('services', 'ServiceController');
    Route::resource('testimonials', 'TestimonialController');
    Route::resource('offers', 'OfferController');
    Route::resource('contacts', 'ContactController');
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::post('settings', 'SettingController@update')->name('settings.update');
    
    Route::resource('products', 'ProductController');
    Route::post('products/delete-media',
    [\App\Http\Controllers\Admin\ProductController::class, 'deleteMedia']
)->name('products.deleteMedia');

   
   });
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});

// Frontend routes
Route::view('/','custom.home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::post('/add-to-cart', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart',         [CartController::class, 'index'])->name('cart.index');
Route::post('/remove-cart', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/update-cart', [CartController::class, 'update'])->name('cart.update');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// User Dashboard
Route::middleware(['auth'])->group(function () {

    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');

});