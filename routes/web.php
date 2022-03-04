<?php

use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Settings\GeneralSettingsController;
use App\Http\Controllers\Settings\PterodactylSettingsController;
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
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

//redirect to mainsite
Route::get('/main', function (GeneralSettings $settings) {
    if ($settings->main_site) return redirect($settings->main_site);
    return redirect()->route('dashboard');
})->name('main-site');

//auth routes
Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('configurations', ConfigurationController::class);
    Route::get('/configurations/{configuration}/clone', [ConfigurationController::class, 'clone'])->name('configurations.clone');
});

//settings
Route::prefix('settings')->name('settings.')->middleware('auth')->group(function () {
    Route::get('general', [GeneralSettingsController::class, 'index'])->name('general.index');
    Route::patch('general', [GeneralSettingsController::class, 'update'])->name('general.update');

    Route::get('pterodactyl', [PterodactylSettingsController::class, 'index'])->name('pterodactyl.index');
    Route::patch('pterodactyl', [PterodactylSettingsController::class, 'update'])->name('pterodactyl.update');
});

//client area
Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
