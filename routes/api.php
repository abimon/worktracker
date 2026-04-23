<?php

use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('myprojects', ProjectController::class);
    Route::post('myprojects/{project}/generate-share-token', [ProjectController::class, 'generateShareToken']);

    Route::apiResource('myprojects.tasks', TaskController::class)->shallow();
    Route::apiResource('myprojects.payments', PaymentController::class)->shallow();
    Route::post('myprojects/{project}/payments/{payment}/mark-completed', [PaymentController::class, 'markAsCompleted']);
    Route::apiResource('myprojects.feedbacks', FeedbackController::class)->shallow();
    Route::post('myprojects/{project}/feedbacks/{feedback}/mark-resolved', [FeedbackController::class, 'markAsResolved']);

    Route::get('myprojects/{project}/collaborators', [CollaboratorController::class, 'index']);
    Route::post('myprojects/{project}/collaborators', [CollaboratorController::class, 'store']);
    Route::put('myprojects/{project}/collaborators/{collaboratorId}', [CollaboratorController::class, 'update']);
    Route::delete('myprojects/{project}/collaborators/{collaboratorId}', [CollaboratorController::class, 'destroy']);
    Route::get('mycollaborations/pending', [CollaboratorController::class, 'pendingInvitations']);

    Route::get('client/profile', [UserController::class, 'profile']);
    Route::put('client/profile', [UserController::class, 'updateProfile']);
    Route::put('client/change-password', [UserController::class, 'changePassword']);
    Route::get('client/dashboard', [UserController::class, 'dashboardStats']);

    Route::post('myverification/documents', [VerificationController::class, 'submitDocument']);
    Route::get('myverification/documents', [VerificationController::class, 'myDocuments']);
    Route::get('myverification/status', [VerificationController::class, 'getStatus']);
});

Route::get('myprojects/shared/{token}', [ProjectController::class, 'showShared']);
Route::get('mycollaborations/accept/{token}', [CollaboratorController::class, 'acceptInvitation']);
Route::get('mycollaborations/decline/{token}', [CollaboratorController::class, 'declineInvitation']);
