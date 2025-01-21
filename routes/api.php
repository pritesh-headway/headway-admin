<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Controllers\API\V1\ProfileController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/sendOtp', [ApiController::class, 'sendOtp']);
Route::post('v1/login', [ApiController::class, 'login']);
Route::post('v1/resend_otp', [ApiController::class, 'resendOtp']);
Route::post('v1/profile/update', [ProfileController::class, 'updateProfile']);
Route::post('v1/profile/getProfile', [ProfileController::class, 'getProfile']);
Route::post('v1/getBlogsList', [ApiController::class, 'getBlogsList']);
Route::post('v1/getPlanList', [ApiController::class, 'getPlanList']);
Route::post('v1/getPlanDetail', [ApiController::class, 'getPlanDetail']);
Route::post('v1/getAddOnsList', [ApiController::class, 'getAddOnsList']);
Route::post('v1/getCoursesList', [ApiController::class, 'getCoursesList']);
Route::post('v1/getCMDVisitList', [ApiController::class, 'getCMDVisitList']);
Route::post('v1/getServicesList', [ApiController::class, 'getServicesList']);
Route::post('v1/getVideoList', [ApiController::class, 'getVideoList']);
Route::post('v1/getTraningVideoList', [ApiController::class, 'getTraningVideoList']);
Route::post('v1/setNotificationUser', [ApiController::class, 'setNotificationUser']);
Route::post('v1/getStartupList', [ApiController::class, 'getStartupList']);
Route::post('v1/getDevelopmentBusinessList', [ApiController::class, 'getDevelopmentBusinessList']);
Route::post('v1/getHRList', [ApiController::class, 'getHRList']);
Route::post('v1/membershipForm', [ApiController::class, 'membershipForm']);
Route::post('v1/contactUs', [ApiController::class, 'contactUs']);
Route::post('v1/getDashboardData', [ApiController::class, 'getDashboardData']);
Route::post('v1/getNotificationUser', [ApiController::class, 'getNotificationUser']);
Route::post('/v1/logout', [ApiController::class, 'logout']);
