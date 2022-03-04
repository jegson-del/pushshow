<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\AuthController;
use App\Http\Controllers\Admins\CampaignController;
use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\NegativeBadgeController;
use App\Http\Controllers\Admins\PositiveBadgeController;
use App\Http\Controllers\Admins\AdminForgotPasswordController;
use App\Http\Controllers\Admins\WithdrawalController;


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


Route::group(['prefix' => 'admins', 'namespace' => 'Admins'], function() {
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/register', [AuthController::class, 'register'])->name('admin.register');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/contact-support', [AdminController::class, 'supports'])->name('admin.show.supports');

    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'forgotPassword'])->name('admin.password.sent');
    Route::post('/reset-password', [AdminForgotPasswordController::class, 'passwordReset'])->name('admin.password.reset');
    
});

Route::group(['prefix' => 'admins', 'namespace' => 'Admins', 'middleware' => 'auth:api', ], function(){
    
    // ADMINS ROUTES
    Route::post('/store', [AdminController::class, 'store']);
    Route::get('/', [AdminController::class, 'queryAll']);
    Route::get('/{admin}', [AdminController::class, 'querySingle']);
    Route::put('/{admin}', [AdminController::class, 'update']);
    Route::delete('/{admin}', [AdminController::class, 'destory']);
    Route::post('/profile/upload/{admin}', [AdminController::class, 'profileUpload']);

    // USERS ROUTES
    Route::get('/fetch/users', [UserController::class, 'queryAll']);
    Route::get('/fetch/users/{user}', [UserController::class, 'querySingle']);
    Route::post('/user/verify/{user}', [UserController::class, 'verifyUser']);

    // NEGATIVE BADGES ROUTES
    Route::post('/negative/store', [NegativeBadgeController::class, 'store']);
    Route::get('/negative/fetch', [NegativeBadgeController::class, 'queryAll']);
    Route::get('/negative/{badge}', [NegativeBadgeController::class, 'querySingle']);
    Route::put('/negative/{badge}', [NegativeBadgeController::class, 'update']);
    Route::delete('/negative/{badge}', [NegativeBadgeController::class, 'destory']);
    Route::post('/negative/add/badge/{badge}/{user}', [NegativeBadgeController::class, 'assignUserBadge']);

    Route::post('/positive/store', [PositiveBadgeController::class, 'store']);
    Route::get('/positive/fetch', [PositiveBadgeController::class, 'queryAll']);
    Route::get('/positive/{badge}', [PositiveBadgeController::class, 'querySingle']);
    Route::put('/positive/{badge}', [PositiveBadgeController::class, 'update']);
    Route::delete('/positive/{badge}', [PositiveBadgeController::class, 'destory']);
    Route::post('/positive/add/badge/{badge}/{user}', [PositiveBadgeController::class, 'assignUserBadge']);

    // CAMPAIGNS ROUTES
    Route::get('/campaign', [CampaignController::class, 'queryAll']);
    Route::post('/campaign/live/{campaign}', [CampaignController::class, 'makeLive']);
    Route::post('/campaign/settle/pushers/{campaign}', [CampaignController::class, 'settlePushers']);
    Route::get('/campaign/{campaign}', [CampaignController::class, 'querySingle']);
    Route::post('/campaign/store', [CampaignController::class, 'store']);
    Route::put('/campaign/update/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaign/delete/{campaign}', [CampaignController::class, 'destory']);

    // WITHDRAWALS
    Route::get('/withdrawals/all', [WithdrawalController::class, 'queryAll']);
    Route::post('/withdrawal/approve/{withdrawal}', [WithdrawalController::class, 'approve']);
    Route::post('/withdrawal/reject/{withdrawal}', [WithdrawalController::class, 'reject']);
    
});
