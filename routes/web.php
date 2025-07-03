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
use App\Http\Controllers\Backend\SchedulesController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\ServicesController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\VideoGalleryController;
use App\Http\Controllers\Backend\StartupController;
use App\Http\Controllers\GalleryController;
use \App\Http\Controllers\OurProductController;
use App\Http\Controllers\ExibitionVisitorController;
use App\Http\Controllers\CandidateRegistrationController;

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
    Route::resource('schedules', SchedulesController::class);
    Route::post('members/addUpdateModuleData', [MembersController::class, 'addUpdateModuleData'])->name('members.addUpdateModuleData');
    Route::post('members/addUpdateStartupModuleData', [MembersController::class, 'addUpdateStartupModuleData'])->name('members.addUpdateStartupModuleData');
    Route::post('members/addUpdateModuleSubjectData', [MembersController::class, 'addUpdateModuleSubjectData'])->name('members.addUpdateModuleSubjectData');
    Route::post('members/addUpdateModuleDataText', [MembersController::class, 'addUpdateModuleDataText'])->name('members.addUpdateModuleDataText');
    Route::post('membership/update/{id}', [MembershipController::class, 'update'])->name('admin.membership.update');
    // Route::get('cms/get-cms-content/{id}', [CmsController::class, 'getContent'])->name('admin.cms.get-cms-content');
    Route::get('/cms/get-cms-content/{id}/{lang}', [CmsController::class, 'getContent'])->name('admin.cms.get-cms-content');
    Route::get('plan/{id}/edit/{type}', [PlanController::class, 'edit'])->name('plan.edit');


    Route::post('members/getSubjectData', [MembersController::class, 'getSubjectData'])->name('members.getSubjectData');


    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');


    Route::get('/exibition-visitors', [ExibitionVisitorController::class, 'index'])->name('exibition_visitors.index');
    Route::get('/exibition-visitors/{id}', [ExibitionVisitorController::class, 'view'])->name('exibition_visitors.view');
    Route::get('/exibition-visitors/create', [ExibitionVisitorController::class, 'create'])->name('exibition_visitors.create');
    Route::post('/exibition-visitors/store', [ExibitionVisitorController::class, 'store'])->name('exibition_visitors.store');
    Route::get('/exibition-visitors/edit/{id}', [ExibitionVisitorController::class, 'edit'])->name('exibition_visitors.edit');
    Route::post('/exibition-visitors/update/{id}', [ExibitionVisitorController::class, 'update'])->name('exibition_visitors.update');
    Route::get('/exibition-visitors/delete/{id}', [ExibitionVisitorController::class, 'destroy'])->name('exibition_visitors.destroy');
    Route::post('/exibition-visitors/export', [ExibitionVisitorController::class, 'export'])->name('exibition_visitors.export');


    Route::get('admin/candidate/form', [CandidateRegistrationController::class, 'create'])->name('candidate.form');
    Route::post('admin/candidate/store', [CandidateRegistrationController::class, 'store'])->name('candidate.store');
    Route::get('admin/candidate', [CandidateRegistrationController::class, 'index'])->name('candidate.index');
    Route::get('admin/candidate/view/{id}', [CandidateRegistrationController::class, 'show'])->name('candidate.view');
    Route::get('admin/candidate/edit/{id}', [CandidateRegistrationController::class, 'edit'])->name('candidate.edit');
    Route::post('admin/candidate/update/{id}', [CandidateRegistrationController::class, 'update'])->name('candidate.update');
    Route::get('admin/candidate/delete/{id}', [CandidateRegistrationController::class, 'destroy'])->name('candidate.delete');
    Route::post('admin/candidate/export', [CandidateRegistrationController::class, 'export'])->name('candidate.export');
})->middleware('auth:admin');

Route::get('api/v1/visitor_form', [ExibitionVisitorController::class, 'form'])->name('exibition_visitors.form');
Route::post('api/v1/visitor-submit', [ExibitionVisitorController::class, 'store'])->name('exibition_visitors.store');
Route::get('api/v1/candidate-form', [CandidateRegistrationController::class, 'form'])->name('candidate.form');
Route::post('api/v1/candidate-form', [CandidateRegistrationController::class, 'store'])->name('candidate.store');

Route::post('admin/ourteam/move-up/{id}', [OurteamController::class, 'moveUp'])->name('admin.ourteam.moveup');
Route::post('admin/ourteam/move-down/{id}', [OurteamController::class, 'moveDown'])->name('admin.ourteam.movedown');