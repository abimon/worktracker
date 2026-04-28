<?php

use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth','verified'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        // Route::get('/dashboard', 'dashboard')->name('dashboard');
    });
    
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::get('/profile', 'profile')->name('profile.show');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::delete('/profile/destroy', 'destroy')->name('profile.destroy');
// updateProfile
// changePassword
        Route::get('/dashboard', 'dashboardStats')->name('dashboard');
    });
    
    Route::resources([
        'projects'=>ProjectController::class,
        'projects.payments'=>PaymentController::class,
        'projects.tasks'=>TaskController::class,
        'projects.feedbacks'=>FeedbackController::class,
    ]);
    Route::resource('projects.collaborators', CollaboratorController::class)->only(['create', 'store', 'destroy', 'update']);
    
    Route::post('projects/{project}/generate-share-token', [ProjectController::class, 'generateShareToken'])->name('projects.generate-share-token');
    Route::post('projects/{project}/payments/{payment}/mark-completed', [PaymentController::class, 'markAsCompleted'])->name('projects.payments.mark-completed');
    Route::post('projects/{project}/feedbacks/{feedback}/mark-resolved', [FeedbackController::class, 'markAsResolved'])->name('projects.feedbacks.mark-resolved');

    Route::controller(CollaboratorController::class)->group(function(){
        Route::get('collaborations/pending', 'pendingInvitations')->name('collaborations.pending');
        Route::get('collaborations/', 'index')->name('collaborations.index');
    });
    
    Route::controller(UserController::class)->group(function(){
        Route::get('user/profile', 'profile');
        Route::put('user/profile', 'updateProfile');
        Route::put('user/change-password', 'changePassword')->name('profile.change-password');
        Route::get('user/dashboard', 'dashboardStats');
    });
    
    Route::controller(VerificationController::class)->group(function(){
        Route::post('verification/documents', 'submitDocument')->name('verification.submit');
        Route::get('verification/documents', 'myDocuments')->name('verification.documents');
        Route::get('verification/status', 'getStatus')->name('verification.status');
    });
});

Route::get('projects/shared/{token}', [ProjectController::class, 'showShared'])->name('projects.shared');
Route::get('collaborations/accept/{token}', [CollaboratorController::class, 'acceptInvitation'])->name('collaborations.accept');
Route::get('collaborations/decline/{token}', [CollaboratorController::class, 'declineInvitation'])->name('collaborations.decline');

require __DIR__.'/auth.php';
