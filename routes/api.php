<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::post('/bookings/{booking}/payments', [BookingController::class, 'submitVisitPayment'])->name('bookings.payments.submit');

// These aliases keep the existing admin dashboard JavaScript working with session auth.
// Because routes/api.php is automatically prefixed with /api, these become:
// /api/admin/halls, /api/admin/packages, etc.
Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/halls', [AdminDashboardController::class, 'getHalls'])->name('halls');
    Route::post('/halls', [AdminDashboardController::class, 'createHall'])->name('halls.store');
    Route::put('/halls/{hall}', [AdminDashboardController::class, 'updateHall'])->name('halls.update');
    Route::delete('/halls/{hall}', [AdminDashboardController::class, 'deleteHall'])->name('halls.destroy');

    Route::get('/packages', [AdminDashboardController::class, 'getPackages'])->name('packages');
    Route::get('/packages/{package}', [AdminDashboardController::class, 'viewPackage'])->name('packages.show');
    Route::post('/packages', [AdminDashboardController::class, 'createPackage'])->name('packages.store');
    Route::put('/packages/{package}', [AdminDashboardController::class, 'updatePackage'])->name('packages.update');
    Route::delete('/packages/{package}', [AdminDashboardController::class, 'deletePackage'])->name('packages.destroy');

    Route::get('/bookings', [AdminDashboardController::class, 'getBookings'])->name('bookings');
    Route::put('/bookings/{booking}/status', [AdminDashboardController::class, 'updateBookingStatus'])->name('bookings.status');

    Route::get('/visits', [AdminDashboardController::class, 'getVisitRequests'])->name('visits');
    Route::post('/visits/{booking}/approve', [AdminDashboardController::class, 'approveVisit'])->name('visits.approve');
    Route::post('/visits/{booking}/reject', [AdminDashboardController::class, 'rejectVisit'])->name('visits.reject');
});

Route::get('/test-api-route', function () {
    return response()->json(['ok' => true, 'message' => 'API route works']);
});
