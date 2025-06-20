<?php

use App\Http\Controllers\ContactController;
use Google\Service\Dataproc\StartupConfig;
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
use App\Http\Controllers\Backend\BatchController;
use App\Http\Controllers\Backend\CoursesController;
use App\Http\Controllers\Backend\CustomersController;
use App\Http\Controllers\Backend\MembersController;
use App\Http\Controllers\Backend\MembershipController;
use App\Http\Controllers\Backend\ModulesController;
use App\Http\Controllers\Backend\OrderAddOnController;
use App\Http\Controllers\Backend\PlanController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\ServicesController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\VideoGalleryController;
use App\Http\Controllers\Backend\StartupController;
use App\Http\Controllers\GalleryController;
use \App\Http\Controllers\OurProductController;

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
    Route::resource('settings', \App\Http\Controllers\Backend\SettingsController::class);
    Route::resource('cms', CmsController::class);
    Route::resource('blogs', BlogsController::class);
    Route::resource('client', ClientController::class);
    Route::resource('ourteam', OurteamController::class);
    Route::resource('event', EventController::class);
    Route::resource('plan', PlanController::class);
    Route::resource('testimonial', TestimonialController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('startups', StartupController::class);
    Route::resource('our_products', OurProductController::class);
    // Route::resource('gallery', GalleryController::class);
    // Gallery Index
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');

    // Create Gallery (requires ?type=ssu or ?type=mmb)
    Route::get('gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery/store', [GalleryController::class, 'store'])->name('gallery.store');

    // Edit Gallery (pass both ID and type in query)
    Route::get('gallery/{id}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');
    Route::post('gallery/{id}/update', [GalleryController::class, 'update'])->name('gallery.update');

    // Delete Gallery (pass both ID and type as route parameters)
    Route::get('gallery/{id}/delete/{type}', [GalleryController::class, 'destroy'])->name('gallery.delete');

    Route::resource('batch', BatchController::class);
    Route::resource('addonservice', AddOnServiceController::class);
    Route::resource('videogallery', VideoGalleryController::class);
    Route::resource('courses', CoursesController::class);
    Route::resource('customers', CustomersController::class);
    Route::resource('membership', MembershipController::class);
    Route::resource('orderaddon', OrderAddOnController::class);
    Route::resource('modules', ModulesController::class);
    Route::resource('members', MembersController::class);
    Route::resource('services', ServicesController::class);
    Route::post('members/addUpdateModuleData', [MembersController::class, 'addUpdateModuleData'])->name('members.addUpdateModuleData');
    Route::post('members/addUpdateModuleDataText', [MembersController::class, 'addUpdateModuleDataText'])->name('members.addUpdateModuleDataText');
    Route::post('membership/update/{id}', [MembershipController::class, 'update'])->name('admin.membership.update');
    // Route::get('cms/get-cms-content/{id}', [CmsController::class, 'getContent'])->name('admin.cms.get-cms-content');
    Route::get('/cms/get-cms-content/{id}/{lang}', [CmsController::class, 'getContent'])->name('admin.cms.get-cms-content');

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');
})->middleware('auth:admin');


Route::post('admin/ourteam/move-up/{id}', [OurteamController::class, 'moveUp'])->name('admin.ourteam.moveup');
Route::post('admin/ourteam/move-down/{id}', [OurteamController::class, 'moveDown'])->name('admin.ourteam.movedown');