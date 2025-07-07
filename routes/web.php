<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChangePasswordController;

Route::middleware('check.user')->group(function () {
    Route::get('/dashboardd', [DashboardController::class,'showDashboardd'])->name('dashboardd');
});

Route::middleware('superadmincheck')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'showDashboard'])->name('dashboard');
    Route::post('/logout',[DashboardController::class,'logout'])->name('dashboard.logout');
    Route::get('/analytics', [DashboardController::class,'analytics'])->name('analytics');
    Route::get('/profile', [DashboardController::class,'profile'])->name('profile');
    Route::post('/update_profile',[FileUploadController::class,'updateProfile'])->name('update_profile');
    Route::post('/user_update_profile/{id}',[FileUploadController::class,'userUpdateProfile'])->name('update_user_profile');
    Route::get('/subscription', [SubscriptionController::class,'subscription_form'])->name('subscription');
    Route::post('/add_subscription', [SubscriptionController::class,'add_subscription'])->name('add_subscription');
    Route::get('/view_users/{id}', [DashboardController::class,'viewDashboard'])->name('viewdashboardusers');
    Route::post('change-password/{id}',[ChangePasswordController::class,'store'])->name('change.password');
    Route::post('change_user_password/{id}',[ChangePasswordController::class,'storeUserPassword'])->name('change.user.password');
});


    





