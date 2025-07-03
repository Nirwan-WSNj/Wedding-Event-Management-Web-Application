<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/halls', function () {
    return view('halls');
})->name('halls');

Route::get('/packages', [PackageController::class, 'getPublicPackages'])->name('packages');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contactUs', function () {
    return view('contactUs');
})->name('contactUs');

Route::get('/package-view', function () {
    return view('SubPackage1');
})->name('package.view');

Route::get('/package-view-2', function () {
    return view('SubPackage2');
})->name('package.view2');

Route::get('/packages/infinity', function () {
    return view('SubPackage3');
})->name('packages.infinity');

// Only one POST route for booking submission is needed

Route::post('/bookings', [BookingController::class, 'store']);

// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

// Authentication test route
Route::get('/auth-test', function () {
    return view('auth-test');
})->name('auth.test');

// Simple login test route
Route::get('/simple-login', function () {
    return view('simple-login');
})->name('simple.login');

// Admin & Manager login test route
Route::get('/admin-manager-login', function () {
    return view('admin-manager-login');
})->name('admin.manager.login');

// Profile debug route
Route::get('/profile-debug', function () {
    return view('profile-debug');
})->name('profile.debug')->middleware('auth');

// Profile upload debug route
Route::get('/profile-upload-debug', function () {
    return view('profile-upload-debug');
})->name('profile.upload.debug')->middleware('auth');

// Customer routes (only for customers)
Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [BookingController::class, 'showBookingForm'])->name('booking');
    Route::post('/booking/progress', [BookingController::class, 'saveProgress'])->name('booking.progress');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/bookings/my', [BookingController::class, 'index'])->name('bookings.my');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
});

// Admin routes (only for admins)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
    Route::get('/dashboard/analytics', [App\Http\Controllers\AdminController::class, 'getAnalytics'])->name('dashboard.analytics');
    Route::get('/dashboard/advanced-analytics', [App\Http\Controllers\AdminController::class, 'getAdvancedAnalytics'])->name('dashboard.advanced-analytics');
    Route::get('/dashboard/recent-activities', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getRecentActivities'])->name('dashboard.recent-activities');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Enhanced Halls Management - All routes redirect to dashboard or work via API
    Route::get('halls', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Hall management is now integrated in the main dashboard. Click "Manage Halls" in the sidebar.');
    })->name('halls.index');
    
    Route::get('halls/create', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Hall creation is now available in the dashboard. Click "Manage Halls" in the sidebar.');
    })->name('halls.create');
    
    Route::get('halls/{hall}', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Hall details are now available in the dashboard. Click "Manage Halls" in the sidebar and use "View Details".');
    })->name('halls.show');
    
    Route::get('halls/{hall}/edit', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Hall editing is now available in the dashboard. Click "Manage Halls" in the sidebar.');
    })->name('halls.edit');
    
    // Keep only API routes for AJAX functionality
    Route::post('halls', [App\Http\Controllers\AdminController::class, 'createHall'])->name('halls.store');
    Route::put('halls/{hall}', [App\Http\Controllers\AdminController::class, 'updateHall'])->name('halls.update');
    Route::delete('halls/{hall}', [App\Http\Controllers\AdminController::class, 'deleteHall'])->name('halls.destroy');
    Route::put('halls/{hall}/status', [HallController::class, 'updateStatus'])->name('halls.status.update');
    Route::get('halls/{hall}/availability', [HallController::class, 'checkAvailability'])->name('halls.availability.check');
    Route::get('halls/{hall}/bookings', [HallController::class, 'getBookings'])->name('halls.bookings');
    
    // API routes for dashboard functionality - Admin Hall Management
    Route::get('/api/admin/halls', [App\Http\Controllers\Admin\HallController::class, 'index'])->name('api.admin.halls');
    Route::get('/api/admin/halls/{hall}', [App\Http\Controllers\Admin\HallController::class, 'show'])->name('api.admin.halls.details');
    Route::post('/api/admin/halls', [App\Http\Controllers\Admin\HallController::class, 'store'])->name('api.admin.halls.store');
    Route::put('/api/admin/halls/{hall}', [App\Http\Controllers\Admin\HallController::class, 'update'])->name('api.admin.halls.update');
    Route::delete('/api/admin/halls/{hall}', [App\Http\Controllers\Admin\HallController::class, 'destroy'])->name('api.admin.halls.destroy');
    Route::put('/api/admin/halls/{hall}/toggle-status', [App\Http\Controllers\Admin\HallController::class, 'toggleStatus'])->name('api.admin.halls.toggle-status');
    Route::post('/api/admin/halls/bulk-action', [App\Http\Controllers\Admin\HallController::class, 'bulkAction'])->name('api.admin.halls.bulk-action');
    Route::get('/api/admin/halls/stats', [App\Http\Controllers\Admin\HallController::class, 'getStats'])->name('api.admin.halls.stats');
    Route::get('/api/admin/halls/{hall}/availability', [App\Http\Controllers\Admin\HallController::class, 'getAvailability'])->name('api.admin.halls.availability');
    Route::get('/halls-data', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getHalls'])->name('halls.data');
    
    // Enhanced Packages Management - All routes redirect to dashboard or work via API
    Route::get('packages', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Package management is now integrated in the main dashboard. Click "Wedding Packages" in the sidebar.');
    })->name('packages.index');
    
    Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    
    // Keep only API routes for AJAX functionality - FIXED DUPLICATE ROUTE NAMES
    Route::post('packages', [App\Http\Controllers\Admin\AdminDashboardController::class, 'createPackage'])->name('packages.store');
    Route::get('packages/{id}/view', [App\Http\Controllers\Admin\AdminDashboardController::class, 'viewPackage'])->name('packages.view');
    Route::put('packages/{id}', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updatePackage'])->name('packages.update');
    Route::delete('packages/{id}', [App\Http\Controllers\Admin\AdminDashboardController::class, 'deletePackage'])->name('packages.destroy');
    Route::put('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::post('packages/{package}/calculate-price', [PackageController::class, 'calculatePrice'])->name('packages.calculate-price');
    
    // API routes for dashboard functionality - Admin Package Management
    Route::get('/api/admin/packages', [App\Http\Controllers\Admin\PackageController::class, 'index'])->name('api.admin.packages');
    Route::get('/api/admin/packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'show'])->name('api.admin.packages.details');
    Route::post('/api/admin/packages', [App\Http\Controllers\Admin\PackageController::class, 'store'])->name('api.admin.packages.store');
    Route::put('/api/admin/packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'update'])->name('api.admin.packages.update');
    Route::delete('/api/admin/packages/{package}', [App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('api.admin.packages.destroy');
    Route::put('/api/admin/packages/{package}/toggle-status', [App\Http\Controllers\Admin\PackageController::class, 'toggleStatus'])->name('api.admin.packages.toggle-status');
    Route::post('/api/admin/packages/bulk-action', [App\Http\Controllers\Admin\PackageController::class, 'bulkAction'])->name('api.admin.packages.bulk-action');
    Route::get('/api/admin/packages/stats', [App\Http\Controllers\Admin\PackageController::class, 'getStats'])->name('api.admin.packages.stats');
    Route::get('/packages-data', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getPackages'])->name('packages.data');
    
    // Export route - redirect to dashboard
    Route::get('packages/export', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Package export is now available in the dashboard. Click "Wedding Packages" in the sidebar and use the "Export" button.');
    })->name('packages.export');
    
    // Enhanced Visit Management
    Route::get('/visits', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getVisitRequests'])->name('visits.index');
    Route::post('/visits/{id}/approve', [App\Http\Controllers\Admin\AdminDashboardController::class, 'approveVisit'])->name('visits.approve');
    Route::post('/visits/{id}/reject', [App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectVisit'])->name('visits.reject');
    
    // Enhanced Booking Management
    Route::get('/bookings', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getBookings'])->name('bookings.index');
    Route::put('/bookings/{id}/status', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updateBookingStatus'])->name('bookings.status.update');
    Route::get('/bookings/{id}/details', [BookingController::class, 'adminShow'])->name('bookings.details');
    
    // Enhanced Users Management
    Route::get('/users', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getUsers'])->name('users');
    Route::get('/users/data', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getUsers'])->name('users.data');
    Route::get('/users/export', [App\Http\Controllers\AdminController::class, 'exportUsers'])->name('users.export');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
    Route::get('/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::post('/users/{id}/update', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Advanced Reporting
    Route::get('/reports/{type}', [App\Http\Controllers\AdminController::class, 'generateReport'])->name('reports.generate');
    Route::get('/reports/export/{type}', [App\Http\Controllers\AdminController::class, 'exportReport'])->name('reports.export');
    
    // System Health & Monitoring
    Route::get('/system/health', [App\Http\Controllers\AdminController::class, 'getSystemHealth'])->name('system.health');
    Route::get('/system/logs', [App\Http\Controllers\AdminController::class, 'getSystemLogs'])->name('system.logs');
    
    // Content Management
    Route::get('/content/home', [App\Http\Controllers\AdminController::class, 'getHomeContent'])->name('content.home');
    Route::put('/content/home', [App\Http\Controllers\AdminController::class, 'updateHomeContent'])->name('content.home.update');
    Route::get('/content/gallery', [App\Http\Controllers\AdminController::class, 'getGalleryContent'])->name('content.gallery');
    Route::post('/content/gallery/upload', [App\Http\Controllers\AdminController::class, 'uploadGalleryImage'])->name('content.gallery.upload');
    Route::delete('/content/gallery/{id}', [App\Http\Controllers\AdminController::class, 'deleteGalleryImage'])->name('content.gallery.delete');
    
    // Sync Queue Management
    Route::prefix('sync-queue')->name('sync-queue.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SyncQueueController::class, 'index'])->name('index');
        Route::get('/{syncQueue}', [App\Http\Controllers\Admin\SyncQueueController::class, 'show'])->name('show');
        Route::post('/{syncQueue}/process', [App\Http\Controllers\Admin\SyncQueueController::class, 'process'])->name('process');
        Route::post('/{syncQueue}/retry', [App\Http\Controllers\Admin\SyncQueueController::class, 'retry'])->name('retry');
        Route::delete('/{syncQueue}', [App\Http\Controllers\Admin\SyncQueueController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [App\Http\Controllers\Admin\SyncQueueController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/run-processor', [App\Http\Controllers\Admin\SyncQueueController::class, 'runProcessor'])->name('run-processor');
        Route::post('/cleanup', [App\Http\Controllers\Admin\SyncQueueController::class, 'cleanup'])->name('cleanup');
        Route::post('/reset-stuck', [App\Http\Controllers\Admin\SyncQueueController::class, 'resetStuck'])->name('reset-stuck');
        Route::get('/api/stats', [App\Http\Controllers\Admin\SyncQueueController::class, 'stats'])->name('stats');
        Route::post('/create-test', [App\Http\Controllers\Admin\SyncQueueController::class, 'createTest'])->name('create-test');
    });
});

// Manager routes (only for managers)
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    
    // Manager section pages
    Route::get('/visits', [ManagerController::class, 'visitSchedules'])->name('visits');
    Route::get('/wedding-requests', [ManagerController::class, 'visitRequests'])->name('wedding_requests');
    Route::get('/bookings', [ManagerController::class, 'allBookings'])->name('bookings');
    Route::get('/calendar', [ManagerController::class, 'calendar'])->name('calendar');
    Route::get('/messages', [ManagerController::class, 'messagesPage'])->name('messages');
    
    Route::get('/dashboard/stats', [ManagerController::class, 'getDashboardStats'])->name('dashboard.stats');
    Route::get('/calendar/events', [ManagerController::class, 'getCalendarEvents'])->name('calendar.events');
    Route::view('/halls', 'manager.halls')->name('halls');
    Route::get('/halls/data', [ManagerController::class, 'getHalls'])->name('halls.data');
    Route::get('/hall/{id}/details', [ManagerController::class, 'getHallDetails'])->name('hall.details');
    Route::get('/visit-schedules', [ManagerController::class, 'visitSchedules'])->name('visit-schedules');
    Route::get('/visit-schedules/pending', [ManagerController::class, 'getPendingVisits'])->name('pending-visits');
    Route::get('/visit-schedules/all', [ManagerController::class, 'getAllVisits'])->name('all-visits');
    Route::get('/visit-schedules/stats', [ManagerController::class, 'getVisitStats'])->name('visit-stats');
    Route::get('/visit/{visitId}', [ManagerController::class, 'getVisitDetails'])->name('visit-details');
    Route::post('/visit/{visitId}/update', [ManagerController::class, 'updateVisit'])->name('update-visit');
    Route::get('/visit-requests', [ManagerController::class, 'visitRequests'])->name('visit.requests');
    
    // CRITICAL WORKFLOW CONTROL ROUTES - Manager approval required
    Route::post('/visit/{id}/approve', [ManagerController::class, 'approveVisit'])->name('visit.approve');
    Route::post('/visit/{id}/reject', [ManagerController::class, 'rejectVisit'])->name('visit.reject');
    Route::post('/booking/{id}/deposit-paid', [ManagerController::class, 'confirmPayment'])->name('deposit.paid');
    
    // NEW: Call confirmation routes for visit approval
    Route::post('/visit/{id}/confirm-by-call', [BookingController::class, 'confirmVisitByCall'])->name('visit.confirm.call');
    Route::get('/visit/{id}/call-history', [BookingController::class, 'getCallHistory'])->name('visit.call.history');
    Route::post('/visit/{id}/schedule-callback', [BookingController::class, 'scheduleCallback'])->name('visit.schedule.callback');
    
    // Workflow monitoring and validation routes
    Route::get('/booking/{id}/workflow-status', [ManagerController::class, 'getBookingWorkflowStatus'])->name('booking.workflow.status');
    Route::get('/booking/{id}/validate-workflow', [ManagerController::class, 'validateBookingWorkflow'])->name('booking.workflow.validate');
    
    // Real-time notification routes
    Route::get('/notifications', [ManagerController::class, 'getNotifications'])->name('notifications');
    Route::get('/notifications/count', [ManagerController::class, 'getNotificationCount'])->name('notifications.count');
    Route::post('/notifications/mark-read', [ManagerController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::post('/notifications/{id}/mark-read', [ManagerController::class, 'markNotificationRead'])->name('notifications.mark-single-read');

    // Additional manager functionality
    Route::post('/bulk-approve-payments', [ManagerController::class, 'bulkApprovePayments'])->name('bulk.approve.payments');
    Route::get('/export-bookings', [ManagerController::class, 'exportBookings'])->name('export.bookings');
    Route::get('/booking/{id}/invoice', [ManagerController::class, 'generateInvoice'])->name('booking.invoice');
    
    // Message system routes
    Route::get('/messages', [ManagerController::class, 'getMessages'])->name('messages.list');
    Route::get('/messages/stats', [ManagerController::class, 'getMessageStats'])->name('messages.stats');
    Route::post('/messages/{id}/read', [ManagerController::class, 'markMessageRead'])->name('messages.read');
    Route::post('/messages/mark-all-read', [ManagerController::class, 'markAllMessagesRead'])->name('messages.mark.all.read');
    Route::post('/messages/{id}/reply', [ManagerController::class, 'replyToMessage'])->name('messages.reply');
    Route::delete('/messages/{id}', [ManagerController::class, 'deleteMessage'])->name('messages.delete');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update', [ManagerController::class, 'updateProfile'])->name('profile.update.manager');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Booking Routes (shared, but protected by auth)
Route::middleware(['auth'])->group(function () {
    Route::post('/booking/check-availability', [BookingController::class, 'checkAvailability']);
    Route::post('/booking/calculate-price', [BookingController::class, 'calculatePrice']);
    Route::post('/booking/submit', [BookingController::class, 'submit'])->name('booking.submit');
    Route::post('/booking/submit-visit', [BookingController::class, 'submitVisitRequest'])->name('booking.submit-visit');
    Route::get('/booking/status/{id}', [BookingController::class, 'getBookingStatus'])->name('booking.status');
    Route::post('/booking/schedule-visit', [BookingController::class, 'scheduleVisit']);
    Route::get('/booking/available-times/{date}', [BookingController::class, 'getAvailableTimeSlots']);
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('booking.my');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/proceed-to-details', [BookingController::class, 'proceedToWeddingDetails'])->name('booking.proceed-to-details');
    // Route::post('/booking/visit/payment', [BookingController::class, 'submitVisitPayment'])->name('booking.visit.payment');
    // Manager-only booking visit status
    Route::group([], function () {
        Route::post('/booking/visit/{visitId}/status', [BookingController::class, 'updateVisitStatus'])->name('booking.visit.update-status');
        Route::get('/booking/visit/{visitId}', [BookingController::class, 'getVisitDetails'])->name('booking.visit.details');
    });
});

// Legal routes
Route::view('/privacy-policy', 'legal.privacy')->name('privacy.policy');
Route::view('/terms-of-service', 'legal.terms')->name('terms.of.service');
Route::view('/cookie-policy', 'legal.cookie')->name('cookie.policy');

// Contact form routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/contact/stats', [ContactController::class, 'getStats'])->name('contact.stats');

// Debug routes
Route::get('/debug/packages', function() {
    $packages = \App\Models\Package::all(['id', 'name', 'price', 'is_active']);
    $halls = \App\Models\Hall::all(['id', 'name', 'price', 'is_active']);
    
    return response()->json([
        'packages' => $packages,
        'halls' => $halls
    ]);
})->name('debug.packages');

require __DIR__.'/auth.php';
require __DIR__.'/debug.php';