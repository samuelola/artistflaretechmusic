<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\PaystackFlareTechWebhookController;
use App\Http\Controllers\TransferController;

Route::middleware('check.user')->group(function () {
    Route::get('/dashboardd', [DashboardController::class,'showDashboardd'])->name('dashboardd');
});

Route::middleware('artistusercheck')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'showDashboard'])->name('dashboard');
    Route::post('/logout',[DashboardController::class,'logout'])->name('dashboard.logout');
    Route::get('/analytics', [DashboardController::class,'analytics'])->name('analytics');
    Route::get('/profile', [DashboardController::class,'profile'])->name('profile');
    Route::post('/update_profile',[FileUploadController::class,'updateProfile'])->name('update_profile');
    Route::post('/user_update_profile/{id}',[FileUploadController::class,'userUpdateProfile'])->name('update_user_profile');
    Route::get('/subscription', [SubscriptionController::class,'subscription_form'])->name('subscription');
    Route::get('/all_subscription', [SubscriptionController::class,'allsubscription'])->name('allsubscription');
    Route::get('/choosesubscription', [SubscriptionController::class,'choosesubscription'])->name('choosesubscription');
    Route::post('/add_subscription', [SubscriptionController::class,'add_subscription'])->name('add_subscription');
    Route::get('/edit_subscription/{id}', [SubscriptionController::class,'edit_subscription'])->name('edit_subscription');
    Route::get('/view_user/{id}', [DashboardController::class,'viewDashboard'])->name('viewdashboardusers');
    Route::post('change-password/{id}',[ChangePasswordController::class,'store'])->name('change.password');
    Route::post('change_user_password/{id}',[ChangePasswordController::class,'storeUserPassword'])->name('change.user.password');
    Route::get('/users',[UserController::class,'allUser'])->name('allUser');
    Route::get('/add_new_user',[UserController::class,'addNewUser'])->name('add_new_user');
    Route::delete('/delete_user/{id}',[UserController::class,'deleteUser'])->name('deleteUser');
    Route::post('/editSub/{id}',[SubscriptionController::class,'editSub'])->name('editSub');
    Route::get('/filter_info',[DashboardController::class,'filterInfo'])->name('filter_info');
    Route::get('/alltracks',[DashboardController::class,'allTracks'])->name('allTracks');
    Route::get('/view_tracks/{id}',[DashboardController::class,'viewTracks'])->name('view_tracks');
    Route::get('/states', [UserController::class,'allState'])->name('all_states');
    Route::post('/create_user', [UserController::class,'createUser'])->name('create_user');
    Route::get('/active_user', [UserController::class,'allActiveUser'])->name('allActiveUser');
    Route::get('/inactive_user', [UserController::class,'allInactiveUser'])->name('allInactiveUser');
    Route::get('users-export', [UserController::class,'export'])->name('users.export');
    Route::get('the_analytics', [AnalyticsController::class,'adminAnalytics'])->name('admin_analytics');
    Route::get('/filter_artist',[AnalyticsController::class,'filterArtistInfo'])->name('filter_artist');
    Route::get('/filter_artist_track',[AnalyticsController::class,'filterArtistTrackInfo'])->name('filter_artist_track');
    Route::get('/filter_artist_album',[AnalyticsController::class,'filterArtistAlbum'])->name('filter_artist_album');

    Route::get('/manage_role', [RoleController::class,'manageRole'])->name('manage_role');
    Route::post('/create_role', [RoleController::class,'createRole'])->name('create_role');
    Route::post('/delete_role', [RoleController::class,'deleteRole'])->name('delete_role');
    Route::post('/update_role', [RoleController::class,'updateRole'])->name('update_role');

    Route::get('/manage_permission', [PermissionController::class,'managePermission'])->name('manage_permission');
    Route::post('/create_permission', [PermissionController::class,'createPermission'])->name('create_permission');
    Route::post('/delete_permission', [PermissionController::class,'deletePermission'])->name('delete_permission');
    Route::post('/update_permission', [PermissionController::class,'updatePermission'])->name('update_permission');

    // assign permission to role 
    Route::get('/assign_permission_role', [PermissionController::class,'assignPermissionRole'])->name('assign_permission_role');
    Route::post('/create_permission_role', [PermissionController::class,'createPermissionRole'])->name('create_permission_role');
    Route::get('/edit_permission_role/{id}', [PermissionController::class,'editPermissionRole'])->name('edit_permission_role');
    Route::post('/update_permission_role', [PermissionController::class,'updatePermissionRole'])->name('update_permission_role');
    Route::post('/delete_permission_role', [PermissionController::class,'deletePermissionRole'])->name('delete_permission_role');
    
    // assign permission to route
    Route::get('/assign_permission_route', [PermissionController::class,'assignPermissionRoute'])->name('assign_permission_route');
    Route::post('/create_permission_route', [PermissionController::class,'createPermissionRoute'])->name('create_permission_route');
    Route::get('/edit_permission_route/{id}', [PermissionController::class,'editPermissionRoute'])->name('edit_permission_route');
    Route::post('/update_permission_route', [PermissionController::class,'updatePermissionRoute'])->name('update_permission_route');

    // subscription process
    Route::post('/checkout_subscription', [CheckoutController::class,'checkoutSubscription'])->name('checkout_subscription');
    
    //topup
    Route::get('/topup', [TopUpController::class,'topup'])->name('topup');
    Route::post('/savetopup', [TopUpController::class,'saveTopup'])->name('savetopup');
    Route::get('/payment_callback', [TopUpController::class,'paymentCallback'])->name('paystack.payment_callback');
    Route::get('/checkout_details/{id}', [CheckoutController::class,'checkoutDetails'])->name('checkout_details');
    Route::post('/checkout_payment', [CheckoutController::class,'checkoutPayment'])->name('checkout_payment');

    // transfer

    Route::get('/transfer', [TransferController::class,'transfer'])->name('transfer');
    Route::post('/transfer_payment', [TransferController::class,'transferPayment'])->name('transfer_payment');
    Route::get('/resolve_account', [TransferController::class,'resolveAccount'])->name('resolve_account');

    Route::get('/user_wallet_transfer', [TransferController::class,'userWalletTransfer'])->name('user_wallet_transfer');
    
    // Route::post('/get_bank_list', [TransferController::class,'getBankList'])->name('get_bank_list');
    
    //webhook
    Route::post('/paystack_flaretech/webhook', [PaystackFlareTechWebhookController::class, 'handle']);
    
});


    





