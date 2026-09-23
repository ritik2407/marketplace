<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminInquiryController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\LocationApiController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Listings Search & Browsing
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');

// Category & Subcategory Listings
Route::get('/category/{category_slug}', [ListingController::class, 'byCategory'])->name('category.show');
Route::get('/category/{category_slug}/{subcategory_slug}', [ListingController::class, 'byCategory'])->name('subcategory.show');

// City Listings
Route::get('/city/{city_slug}', [ListingController::class, 'byCity'])->name('city.show');

// City + Category Combined Listings
Route::get('/city/{city_slug}/category/{category_slug}', [ListingController::class, 'byCityAndCategory'])->name('city.category.show');

// Public Listing Detail Page
Route::get('/listings/{slug}', [ListingController::class, 'show'])->name('listings.show');

// Inquiries / Contact
Route::post('/listings/{id}/inquiry', [ListingController::class, 'sendInquiry'])->name('listings.inquiry');

// Auth Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Create & Manage Listings
    Route::get('/post-ad', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/post-ad', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{id}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{id}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{id}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::patch('/listings/{id}/toggle-status', [ListingController::class, 'toggleStatus'])->name('listings.toggle-status');
    Route::post('/listings/{id}/favorite', [ListingController::class, 'toggleFavorite'])->name('listings.favorite');

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Admin Panel Routes (Restricted by AdminMiddleware)
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Manage Listings
    Route::get('/listings', [AdminListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/{id}/edit', [AdminListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{id}', [AdminListingController::class, 'update'])->name('listings.update');
    Route::post('/listings/{id}/images', [AdminListingController::class, 'uploadImages'])->name('listings.images.upload');
    Route::patch('/images/{id}/primary', [AdminListingController::class, 'setPrimaryImage'])->name('images.primary');
    Route::delete('/images/{id}', [AdminListingController::class, 'deleteImage'])->name('images.destroy');
    Route::patch('/listings/{id}/featured', [AdminListingController::class, 'toggleFeatured'])->name('listings.featured');
    Route::patch('/listings/{id}/status', [AdminListingController::class, 'updateStatus'])->name('listings.status');
    Route::delete('/listings/{id}', [AdminListingController::class, 'destroy'])->name('listings.destroy');

    // Manage Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{id}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Manage Categories & Subcategories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/{id}/subcategories', [AdminCategoryController::class, 'storeSubcategory'])->name('subcategories.store');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::delete('/subcategories/{id}', [AdminCategoryController::class, 'destroySubcategory'])->name('subcategories.destroy');

    // Manage Locations
    Route::get('/locations', [AdminLocationController::class, 'index'])->name('locations.index');
    Route::post('/locations/cities', [AdminLocationController::class, 'storeCity'])->name('locations.cities.store');
    Route::patch('/locations/cities/{id}/popular', [AdminLocationController::class, 'togglePopularCity'])->name('locations.cities.popular');
    Route::post('/locations/areas', [AdminLocationController::class, 'storeArea'])->name('locations.areas.store');
    Route::delete('/locations/cities/{id}', [AdminLocationController::class, 'destroyCity'])->name('locations.cities.destroy');
    Route::delete('/locations/areas/{id}', [AdminLocationController::class, 'destroyArea'])->name('locations.areas.destroy');

    // Manage Inquiries
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::delete('/inquiries/{id}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
});

// Dynamic Cascading Selectors (States, Cities, Areas, Subcategories)
Route::prefix('api/cascade')->group(function () {
    Route::get('/states', [LocationApiController::class, 'getStates'])->name('cascade.states');
    Route::get('/cities', [LocationApiController::class, 'getCities'])->name('cascade.cities');
    Route::get('/areas', [LocationApiController::class, 'getAreas'])->name('cascade.areas');
    Route::get('/subcategories', [LocationApiController::class, 'getSubcategories'])->name('cascade.subcategories');
});
