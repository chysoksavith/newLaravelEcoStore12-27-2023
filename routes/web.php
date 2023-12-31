<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\brandController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\ProdutController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\frontEnd\FrontController;

// Route::get('/', function () {
//     return view('welcome');
// });

// front end

Route::controller(FrontController::class)->group(function(){
    Route::get('/', 'index')->name('front.home');
    Route::get('/homePage','homeIndex')->name('front.indexPage');
});





// backEnd
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        //
        Route::controller(AdminLoginController::class)->group(function () {
            Route::get('/login', 'index')->name('admin.login');
            Route::post('/authenticate', 'authenticate')->name('admin.authenticate');
        });
    });

    Route::group(['middleware' => 'admin.auth'], function () {

        Route::controller(HomeController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('admin.dashboard');
            Route::get('/logout', 'logout')->name('admin.logout');
        });

        // Category
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('category.index');
            Route::get('/categories/create', 'create')->name('category.create');
            Route::post('/categories', 'store')->name('category.store');
            Route::get('/categories/{category}/edit', 'edit')->name('category.edit');
            Route::put('/categories/{category}', 'update')->name('category.update');
            Route::delete('/categories/{category}', 'destroy')->name('category.destroy');
        });
        // sub category
        Route::controller(SubCategoryController::class)->group(function(){
            Route::get('/sub-categories', 'index')->name('sub-category.index');
            Route::get('/sub-categories/create', 'create')->name('sub-category.create');
            Route::post('/sub-categories/store', 'store')->name('sub-category.store');
            Route::get('/sub-categories/{subCategory}/edit', 'edit')->name('sub-category.edit');
            Route::put('/sub-categories/{subCategory}', 'update')->name('sub-category.update');
            Route::delete('/sub-categories/{subCategory}', 'destroy')->name('sub-category.destroy');

        });
        // brand
        Route::controller(brandController::class)->group(function(){
            Route::get('/brand', 'index')->name('brand.index');
            Route::get('/brand/create', 'create')->name('brand.create');
            Route::post('/brand/store', 'store')->name('brand.store');
            Route::get('/brand/{brandId}/edit', 'edit')->name('brand.edit');
            Route::put('/brand/{brandId}', 'update')->name('brand.update');
            Route::delete('/brand/{brandId}', 'destroy')->name('brand.destroy');

        });
        // product
        Route::controller(ProdutController::class)->group(function(){
            Route::get('/product', 'index')->name('product.index');
            Route::get('/product/create', 'create')->name('product.create');
            Route::post('/product', 'store')->name('product.store');
            Route::get('/product/{productId}/edit', 'edit')->name('product.edit');
            Route::put('/product/{productId}', 'update')->name('product.update');
            Route::delete('/product/{productId}', 'destroy')->name('product.destroy');

        });
        //sub product category
        Route::get('/product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');
        // image update
        Route::controller(ProductImageController::class)->group(function(){
            Route::post('/product-image/update', 'update')->name('product-images.update');
            Route::delete('/product-image', 'destroy')->name('product-images.delete');

        });
    });
    // get slug
    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug'); //end method;
    // temp-image-create

    Route::post('/upload-temp-image', [TempImageController::class, 'store'])->name('temp-images-create');
});
