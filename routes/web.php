<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandlordScreeningController;
use App\Http\Controllers\RentalBookingController;
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



Route::middleware(['auth', 'role:landlord'])
    ->prefix('landlord')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [LandlordScreeningController::class, 'index'])
            ->name('landlord.dashboard');

        // Verification page
        Route::get('/verification', [LandlordScreeningController::class, 'verification'])
            ->name('landlord.verification');

        // Store files in SESSION (not database yet)
        Route::post('/verification/store-session', [LandlordScreeningController::class, 'storeInSession'])
            ->name('landlord.verification.storeSession');

        // Delete files from SESSION
        Route::delete('/verification/delete-session/{type}', 
            [LandlordScreeningController::class, 'deleteFromSession']
        )->name('landlord.verification.deleteSession');

        // FINALIZE - Save to database after 100% progress
        Route::post('/verification/finalize', [LandlordScreeningController::class, 'finalizeVerification'])
            ->name('landlord.verification.finalize');

        // Background Screening page
        Route::get('/verification/screening', [LandlordScreeningController::class, 'screening'])
            ->name('landlord.verification.screening');

        // Save screening data (AJAX)
        Route::post('/verification/save-screening', [LandlordScreeningController::class, 'saveScreeningToSession'])
            ->name('landlord.verification.saveScreeningSession');

        // Delete from database (if needed)
        Route::delete('/verification/delete/{type}', 
            [LandlordScreeningController::class, 'deleteDocument']
        )->name('landlord.verification.delete');

        Route::get(
            '/verification/preview/{type}',
            [LandlordScreeningController::class, 'previewSessionFile']
        )->name('landlord.verification.preview');

         Route::get('/verification/application',
            [LandlordScreeningController::class, 'viewApplication']
        )->name('landlord.verification.view');

        // Edit application (ONLY pending / rejected)
        Route::post('/verification/application/edit',
            [LandlordScreeningController::class, 'editApplication']
        )->name('landlord.verification.update');

        Route::post(
            '/verification/withdraw',
            [LandlordScreeningController::class, 'withdraw']
        )->name('landlord.verification.withdraw');

    });


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // Admin dashboard
        Route::get('/dashboard', function () {
            return view('auth.admin-auth.admin-dashboard');
        })->name('admin.dashboard');

        // Verification list (PENDING ONLY)
        Route::get('/verifications',
            [LandlordScreeningController::class, 'adminVerificationIndex']
        )->name('admin.verifications.index');

        // View verification details
        Route::get('/verifications/{landlord}',
            [LandlordScreeningController::class, 'adminVerificationShow']
        )->name('admin.verifications.show');

        // Actions
        Route::post('/verifications/{landlord}/approve',
            [LandlordScreeningController::class, 'adminApproveVerification']
        )->name('admin.verifications.approve');

        Route::post('/verifications/{landlord}/reject',
            [LandlordScreeningController::class, 'adminRejectVerification']
        )->name('admin.verifications.reject');

        Route::post('/verifications/{landlord}/resubmission',
            [LandlordScreeningController::class, 'adminRequestResubmission']
        )->name('admin.verifications.resubmit');
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

//Rental Booking Process
Route::post('/landlord/listings', [RentalBookingController::class, 'createListing'])
     ->name('landlord.listings.store');

Route::post(
    '/landlord/listings/save-draft',
    [RentalBookingController::class, 'saveListingDraft']
)->name('landlord.listings.saveDraft');





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




