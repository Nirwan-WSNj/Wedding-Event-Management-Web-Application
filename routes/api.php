<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::post('/bookings/{booking}/payments', [BookingController::class, 'submitVisitPayment'])->name('bookings.payments.submit');

Route::get('/test-api-route', function () { return 'API route works'; });
