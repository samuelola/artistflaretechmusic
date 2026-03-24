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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MusicFormController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ArtistOwnershipIdentityController;




Route::get('/clear', [CacheController::class, 'clear'])->name('clear');
Route::get('/clearer', [CacheController::class, 'clearer']);
Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/refresh-csrf', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('refresh_token');

Route::get('/share/{id}', [DashboardController::class, 'share'])->name('share_track');

Route::controller(PaystackFlareTechWebhookController::class)->group(function () {
            
        Route::post('/paystack_flaretech/webhook','handle');
});

Route::middleware('check.user')->group(function () {
    Route::get('/dashboardd', [DashboardController::class,'showDashboardd'])->name('dashboardd');
});

Route::middleware(['artistusercheck','check.subscription'])->group(function () {

    
    Route::controller(QueueController::class)->group(function () {

         Route::post('/trigger-queue','triggerQueue');
    });

    Route::controller(DashboardController::class)->group(function () {
        
        Route::get('/send_test','testnoti')->name('send_test');
        Route::get('/dashboard','showDashboard')->name('dashboard');
        Route::post('/logout','logout')->name('dashboard.logout');
        Route::get('/analytics','analytics')->name('analytics');
        Route::get('/profile','profile')->name('profile');
        Route::get('/view_user/{id}','viewDashboard')->name('viewdashboardusers');
        Route::get('/filter_info','filterInfo')->name('filter_info');
        Route::get('/the_tracks/{id}','theTracks')->name('theTracks');
        Route::get('/view_tracks/{id}','viewTracks')->name('view_tracks');
        Route::get('/download/{id}', 'download')->name('download_track');
        
    });

    Route::controller(FileUploadController::class)->group(function () {
         Route::post('/update_profile','updateProfile')->name('update_profile');
         Route::post('/user_update_profile/{id}','userUpdateProfile')->name('update_user_profile');
    });

    Route::controller(SubscriptionController::class)->group(function () {
        Route::get('/subscription','subscription_form')->name('subscription');
        Route::get('/all_subscription','allsubscription')->name('allsubscription');
        Route::get('/choosesubscription','choosesubscription')->name('choosesubscription');
        Route::post('/add_subscription','add_subscription')->name('add_subscription');
        Route::get('/edit_subscription/{id}','edit_subscription')->name('edit_subscription');
        Route::post('/editSub/{id}','editSub')->name('editSub');
    });

    Route::controller(ChangePasswordController::class)->group(function () {
         Route::post('change-password/{id}','store')->name('change.password');
         Route::post('change_user_password/{id}','storeUserPassword')->name('change.user.password');
    });
    
    
    Route::controller(UserController::class)->group(function () {
        Route::get('/users','allUser')->name('allUser');
        Route::get('/add_new_user','addNewUser')->name('add_new_user');
        Route::delete('/delete_user/{id}','deleteUser')->name('deleteUser');
        Route::get('/states','allState')->name('all_states');
        Route::post('/create_user','createUser')->name('create_user');
        Route::get('/active_user','allActiveUser')->name('allActiveUser');
        Route::get('/inactive_user','allInactiveUser')->name('allInactiveUser');
        Route::get('users-export','export')->name('users.export');
    });

    Route::controller(AnalyticsController::class)->group(function () {
        
        Route::get('the_analytics','adminAnalytics')->name('admin_analytics');
        Route::get('/filter_artist','filterArtistInfo')->name('filter_artist');
        Route::get('/filter_artist_track','filterArtistTrackInfo')->name('filter_artist_track');
        Route::get('/filter_artist_album','filterArtistAlbum')->name('filter_artist_album');

    });

    Route::controller(RoleController::class)->group(function () {
         Route::get('/manage_role','manageRole')->name('manage_role');
         Route::post('/create_role','createRole')->name('create_role');
         Route::post('/delete_role','deleteRole')->name('delete_role');
         Route::post('/update_role','updateRole')->name('update_role');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/manage_permission','managePermission')->name('manage_permission');
        Route::post('/create_permission','createPermission')->name('create_permission');
        Route::post('/delete_permission','deletePermission')->name('delete_permission');
        Route::post('/update_permission','updatePermission')->name('update_permission');

        // assign permission to role 
        Route::get('/assign_permission_role','assignPermissionRole')->name('assign_permission_role');
        Route::post('/create_permission_role','createPermissionRole')->name('create_permission_role');
        Route::get('/edit_permission_role/{id}','editPermissionRole')->name('edit_permission_role');
        Route::post('/update_permission_role','updatePermissionRole')->name('update_permission_role');
        Route::post('/delete_permission_role','deletePermissionRole')->name('delete_permission_role');
        
        // assign permission to route
        Route::get('/assign_permission_route','assignPermissionRoute')->name('assign_permission_route');
        Route::post('/create_permission_route','createPermissionRoute')->name('create_permission_route');
        Route::get('/edit_permission_route/{id}','editPermissionRoute')->name('edit_permission_route');
        Route::post('/update_permission_route','updatePermissionRoute')->name('update_permission_route');
        
    });

    Route::controller(CheckoutController::class)->group(function () {
            // subscription process
        Route::post('/checkout_subscription','checkoutSubscription')->name('checkout_subscription');
        Route::get('/checkout_details/{id}','checkoutDetails')->name('checkout_details');
        Route::post('/checkout_payment','checkoutPayment')->name('checkout_payment');
        Route::post('/checkout_coin_payment','checkoutCoinPayment')->name('checkout_coin_payment');
    });

    Route::controller(TopUpController::class)->group(function () {
        Route::get('/topup','topup')->name('topup');
        Route::post('/savetopup','saveTopup')->name('savetopup');
        Route::get('/payment_callback','paymentCallback')->name('paystack.payment_callback');
        
    });

    Route::controller(TransferController::class)->group(function () {
            
        Route::get('/transfer','transfer')->name('transfer');
        Route::post('/transfer_payment','transferPayment')->name('transfer_payment');
        Route::post('/resolve_account','resolveAccount')->name('resolve_account');
        Route::post('/user_wallet_transfer','userWalletTransfer')->name('user_wallet_transfer');
        Route::post('/user_coin_transfer','userCoinTransfer')->name('user_coin_transfer');

    });

    


    Route::controller(PaymentController::class)->group(function () {
         Route::get('/payment','Payments')->name('payment');
         Route::get('/earnings','Earnings')->name('earnings');
         Route::get('/split_sheet','splitSheet')->name('split_sheet');
         Route::get('/split-sheet/{release}','getTracks')
         ->name('split.sheet.tracks');
    });

    Route::controller(TransactionController::class)->group(function () {
        Route::get('/transactions','transactions')->name('transactions');
    });

    Route::controller(ReleaseController::class)->group(function () {
        Route::get('/music_product','musicProduct')->name('music_product');
        Route::get('/labels','musicLabels')->name('music_labels');
        Route::get('/artists','musicArtist')->name('music_artist');
        Route::get('/music_release','musicRelease')->name('music_release');
        Route::post('/store_music_release','storeMusicRelease')->name('store_music_release');
        Route::get('/fetch_music/{id}', 'fetchMusic')->name('fetchMusic');
        Route::post('/start_music', 'startMusicRelease')->name('start_music_release');
        Route::get('/last_release','getLastRelease')->name('last_release');
        Route::get('/edit_music_product/{id}','editMusicProduct')->name('edit_music_product');
        Route::post('/update_music','updateMusicRelease')->name('update_music_release');
        

        
    });



    Route::controller(ProxyController::class)->group(function () {

           Route::get('/proxy/image/{filename}', 'image');
    });

    Route::controller(CatalogController::class)->group(function () {

           Route::get('monetize-songs', 'songUpload')->name('flaretech-monetize-songs');     

    });

    
    
     Route::controller(MusicFormController::class)->group(function () {

        Route::post('/verification/store','verification')->name('verification.store');

        // Youtube validation
        Route::post('/release_youtube_valid','youtubeValidation')->name('releases.youtube');
            
        Route::get('/releases/create','showStep')->name('releases.create');
        // Ajax endpoints
        Route::post('/releases/ajax-save-step','ajaxSaveStep')->name('releases.ajax.save');
        Route::post('/releases/upload-audio','uploadAudio')->name('releases.upload.audio');
        Route::post('/releases/upload-artwork','uploadArtwork')->name('releases.upload.artwork');
        Route::post('/releases/generate-isrc','generateIsrc')->name('releases.generate.isrc');
        Route::post('/releases/save-tracks','saveTrackDetails')->name('releases.save.tracks');
        Route::post('/releases/save-outlets','saveOutlets')->name('releases.save.outlets');
        Route::post('/releases/submit-final', 'submitFinal')->name('releases.submit.final');
        Route::get('/releases/load-draft', 'loadDraft')->name('releases.load.draft');
        Route::get('/edit_music/{id}','editMusicProductForm')->name('edit_music_form');
        Route::get('/releases/load-edit/{id}','loadEditRelease')->name('releases.load.edit');

        Route::put('/update_basic/{id}', 'updateBasic')->name('release.update.basic');
        Route::post('/update_artwork/{id}', 'updateArtwork')->name('release.update.artwork');
        Route::post('/update_audios/{id}', 'updateAudio')->name('release.update.audio');
        Route::put('/update_tracks/{id}', 'updateTracks')->name('release.update.tracks');
        Route::put('/update_outlets/{id}', 'updateOutlets')->name('release.update.outlets');
        Route::put('/update_verification/{id}', 'updateVerification')->name('release.update.veri');
        Route::post('update_final/{id}', 'submitFinalUpdate')->name('release.update.final');
        Route::delete('/delete_audio/{track}','deleteAudio')->name('release.delete.audio');
        Route::get('/get_tracks/{id}', 'getTracks');
        Route::post('/clear_audios','clearAllAudios')->name('music.clearAudios');
        Route::post('/delete_audio_track','deleteAudioTrack');
        Route::get('/audio_upload/status/{cacheKey}','audioUploadStatus');
        Route::get('/check_audio_upload/{cacheKey}', 'checkAudioStatus');
    });


     Route::controller(WithdrawController::class)->group(function () {
        Route::get('/withdraw','withdrawCoin')->name('withdraw');
        Route::post('/withdraw_store','withdrawStoreCoin')->name('withdraw_store');
    });

     Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications','index')->name('notifications.index');
        Route::post('/notifications/mark-as-read','markNotifications');
        Route::get('/notifications/load-more','loadMoreNotifications')->name('notifications.loadMore');
        Route::get('/notifications/{id}/read', 'read')->name('notifications.read');
    });


    Route::controller(ArtistOwnershipIdentityController::class)->group(function () {

          Route::post('/artist/step1', 'storeStep1')->name('artist.step1');
          Route::post('/artist/step2', 'storeStep2')->name('artist.step2');
          Route::post('/artist/step3', 'storeStep3')->name('artist.step3');
          Route::post('/artist/step4', 'storeStep4')->name('artist.step4');
          Route::post('/artist/step5', 'storeStep5')->name('artist.step5');
          Route::post('/artist/step6', 'storeStep6')->name('artist.step6');
          Route::post('/artist/final-submit', 'finalSubmit')->name('artist.final.submit');
    });




    
});


    


    





