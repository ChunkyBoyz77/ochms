<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:ocs'])->group(function () {
    Route::get('/ocs/dashboard', function () {
        return view('auth.ocs-auth.ocs-dashboard');
    })->name('auth.ocs-dashboard');
});

Route::middleware(['auth', 'role:landlord'])->group(function () {
    Route::get('/landlord/dashboard', function () {
        return view('auth.landlord-auth.landlord-dashboard');
    })->name('landlord.dashboard');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';

// Custom registration flow

//Become Landlord Promo page
Route::get('/become-landlord', function () {
    return view('manage_landlord_screening.become-landlord');
})->name('landlord.become');

Route::get('/landlord/login', function () {
    return view('auth.landlord-auth.landlord-authenticate');
})->name('landlord.auth');

Route::get('/admin/login', function () {
    return view('auth.admin-auth.admin-login');
})->name('admin.login');

Route::get('/admin/dashboard', function () {
    return view('auth.admin-auth.admin-dashboard');
})->name('auth.admin-dashboard');




// Step 2: Register as specific role
Route::get('/register/ocs', [RegisteredUserController::class, 'createOcs'])->name('register.ocs');
Route::get('/register/landlord', [RegisteredUserController::class, 'createLandlord'])->name('register.landlord');
Route::get('/register/admin', [RegisteredUserController::class, 'createAdmin'])->name('register.admin');

// Step 3: Store registration form (still same controller@store)
Route::post('/register/ocs', [RegisteredUserController::class, 'storeOcs'])->name('register.ocs.store');
Route::post('/register/landlord', [RegisteredUserController::class, 'storeLandlord'])->name('register.landlord.store');
Route::post('/register/admin', [RegisteredUserController::class, 'storeAdmin'])->name('register.admin.store');

// -------------------------
// LANDLORD DASHBOARD ROUTES
// -------------------------

// Main dashboard
Route::get('/landlord/dashboard', function () {
    return view('manage_rental_booking.landlord-dashboard');
})->name('landlord.dashboard');


// Manage listings page (placeholder)
Route::get('/landlord/listings', function () {
    return view('manage_rental_booking.landlord-rentallist');
})->name('landlord.listings');

// Manage listings page (placeholder)
Route::get('/landlord/create-listings', function () {
    return view('manage_rental_booking.create-listings-form');
})->name('landlord.createlistings');



// Manage booking requests page (placeholder)
Route::get('/landlord/bookings', function () {
    return view('landlord.bookings');
})->name('landlord.bookings');


Route::get('/ocs/rentals', function () {
    return view('ocs.search-results');
});

Route::get('/ocs/property/details', function () {
    return view('ocs.property-details');
})->name('ocs.property.details');


Route::get('/ocs/resources/pantry', function () {
    return view('ocs.resources-pantry');
})->name('ocs.resources.pantry');




