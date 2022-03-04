<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\UserWithdrawalController;
use App\Http\Controllers\Users\ContactController;
use App\Http\Controllers\Users\VerificationController;
use App\Http\Controllers\Users\CampaignController;

use App\Http\Controllers\Subscribers\AuthController as AuthSubscriber;
use App\Http\Controllers\Subscribers\SubscriberController;
use App\Http\Controllers\Subscribers\SubscriberForgotPasswordController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// USERS AUTH ROUTES
Route::group(['prefix' => 'users', 'namespace' => 'Users'], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Email Verification
    // Route::get('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    //
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.sent');
    Route::post('/reset-password', [AuthController::class, 'passwordReset'])->name('password.reset');

    //
    Route::post('/contact-support', [ContactController::class, 'create']); 
});

// USERS PROTECTED ROUTES
Route::group(['prefix' => 'users', 'namespace' => 'Users', 'middleware' => ['auth:api', 'userVerified', 'disabled']], function() {
    Route::post('/update/{user}', [UserController::class, 'update']);
    Route::get('/{user}', [UserController::class, 'getUser']);
    Route::post('/profile/upload/{user}', [UserController::class, 'profileUpload'])->name('api.user.profile');

    // WITHDRAWAL
    Route::post('/withdrawal', [UserWithdrawalController::class, 'create'])->name('api.user.create.withdrawal');
    Route::get('/withdrawal/{user}', [UserWithdrawalController::class, 'show'])->name('api.user.show.withdrawal');
    Route::delete('/withdrawal/remove/{withdrawal}', [UserWithdrawalController::class, 'destory']);

    // Campaigns
    Route::get('/see/live/campaigns', [CampaignController::class, 'onlineCampaigns']);
    Route::get('/fetch/campaigns/{user}', [CampaignController::class, 'userCampaigns']);
    Route::get('/fetch/target/campaign/{reference}', [CampaignController::class, 'targetCampaign']); // this will be used to retrive with key pcfb-, pcin-, pcyt-
    Route::put('/campaign/update/{campaign}', [CampaignController::class, 'updateCampaign']);
    Route::post('/campaign/confirm/target/link/{user}/{campaign}/{reference}', [CampaignController::class, 'confirmTargetLink']);
    Route::post('/campaign/completed/{campaign}/{user}/{subscriber}', [CampaignController::class, 'closeCampaign']);
});



// SUBSCRIBERS AUTHENTICATION ROUTES
Route::group(['prefix' => 'subscribers', 'namespace' => 'Subscribers'], function() {
    Route::post('/login', [AuthSubscriber::class, 'login'])->name('api.subscriber.login');
    Route::post('/register', [AuthSubscriber::class, 'register'])->name('api.subscriber.register');

    Route::post('/forgot-password', [SubscriberForgotPasswordController::class, 'forgotPassword'])->name('subscriber.password.sent');
    Route::post('/reset-password', [SubscriberForgotPasswordController::class, 'passwordReset'])->name('subscriber.password.reset');
});

// SUBSCRIBERS PROTECTED ROUTES
Route::group(['prefix' => 'subscribers', 'namespace' => 'Subscriber', 'middleware' => 'auth:api'], function() {
    Route::post('/update/{subscriber}', [SubscriberController::class, 'update']);
    Route::get('/{subscriber}', [SubscriberController::class, 'getSubscriber']);
    Route::post('/profile/upload/{subscriber}', [SubscriberController::class, 'profileUpload'])->name('api.subscriber.profile');

    Route::post('/campaign/buy/{subscriber}', [SubscriberController::class, 'buyCampaign'])->name('buy.campaign');
});
