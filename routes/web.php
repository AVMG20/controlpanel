<?php

use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServerController as AdminServerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Settings\GeneralSettingsController;
use App\Http\Controllers\Settings\PterodactylSettingsController;
use App\Http\Controllers\Settings\SmtpSettingsController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Route;


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
//public
Route::redirect('/', '/dashboard')->name('home');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'createServer'])->name('checkout.store');

//redirect to mainsite
Route::get('/main', function (GeneralSettings $settings) {
    if ($settings->main_site) return redirect($settings->main_site);
    return redirect()->route('dashboard.index');
})->name('main-site');

//auth routes
Auth::routes();


//client area
Route::middleware('auth')->group(function () {
    Route::resource('dashboard', HomeController::class);

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});


//admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('servers', AdminServerController::class);
    Route::resource('configurations', ConfigurationController::class);
    Route::get('/configurations/{configuration}/clone', [ConfigurationController::class, 'clone'])->name('configurations.clone');
    Route::resource('notifications', NotificationTemplateController::class);
    Route::post('/notifications/send/{notification}', [NotificationTemplateController::class, 'sendTestNotification'])->name('notifications.test');
    Route::post('/notifications/preview/{notification}', [NotificationTemplateController::class, 'previewNotification'])->name('notifications.preview');
});

//settings
Route::prefix('settings')->name('settings.')->middleware('auth')->group(function () {
    Route::get('general', [GeneralSettingsController::class, 'index'])->name('general.index');
    Route::patch('general', [GeneralSettingsController::class, 'update'])->name('general.update');

    Route::get('pterodactyl', [PterodactylSettingsController::class, 'index'])->name('pterodactyl.index');
    Route::patch('pterodactyl', [PterodactylSettingsController::class, 'update'])->name('pterodactyl.update');

    Route::get('smtp', [SmtpSettingsController::class, 'index'])->name('smtp.index');
    Route::patch('smtp', [SmtpSettingsController::class, 'update'])->name('smtp.update');
});


