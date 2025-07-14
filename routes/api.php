<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProgramStudiController;
use App\Http\Controllers\Api\FeedbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-admin', [AuthController::class, 'registerAdmin']); // For first admin only

// Public Feedback Routes (accessible without login)
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/feedback/stats', [FeedbackController::class, 'getStats']);
Route::get('/feedback/{id}', [FeedbackController::class, 'show']);


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/check-admin', [AuthController::class, 'checkAdmin']);

    Route::post('/feedback', [FeedbackController::class, 'store']); // Can be anonymous
    
    // User Feedback Routes (require authentication)
    Route::get('/feedback/my', [FeedbackController::class, 'myFeedback']);
    Route::put('/feedback/{id}', [FeedbackController::class, 'update']);
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);
    
    // Admin Routes - Protected by admin middleware
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::put('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdminStatus']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::get('/stats', [AdminController::class, 'getStats']);
        
        // Admin Feedback Management
        Route::get('/feedback', [AdminController::class, 'getAllFeedback']);
        Route::put('/feedback/{id}/reply', [AdminController::class, 'replyToFeedback']);
        Route::delete('/feedback/{id}', [AdminController::class, 'deleteFeedback']);
    });
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

// Program Studi API Routes
Route::get('/program-studi', [ProgramStudiController::class, 'index']);
Route::get('/program-studi/{code}', [ProgramStudiController::class, 'show']);

// Laporan API Routes
use App\Http\Controllers\API\LaporanController;

// Public routes for laporan
Route::get('/laporan', [LaporanController::class, 'index']);
Route::get('/laporan/{id}', [LaporanController::class, 'show']);
Route::get('/laporan/status/{status}', [LaporanController::class, 'getByStatus']);
Route::post('/laporan', [LaporanController::class, 'store']);

// Protected routes for laporan that require authentication
Route::middleware('auth:api')->group(function () {
    Route::get('/laporan/my', [LaporanController::class, 'myLaporan']);
    Route::get('/laporan/stats', [LaporanController::class, 'getStats']);
    Route::put('/laporan/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);
});