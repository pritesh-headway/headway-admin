<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BlogsController;
use App\Http\Controllers\Backend\CmsController;
use App\Http\Controllers\Backend\ClientController;
use App\Http\Controllers\Backend\OurteamController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\AddOnServiceController;
use App\Http\Controllers\Backend\PlanController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\VideoGalleryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('admins', AdminsController::class);
    Route::resource('banner', BannerController::class);
    Route::resource('cms', CmsController::class);
    Route::resource('blogs', BlogsController::class);
    Route::resource('client', ClientController::class);
    Route::resource('ourteam', OurteamController::class);
    Route::resource('event', EventController::class);
    Route::resource('plan', PlanController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('addonservice', AddOnServiceController::class);
    // Route::resource('hr', HrController::class);
    Route::resource('videogallery', VideoGalleryController::class);

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');
})->middleware('auth:admin');
