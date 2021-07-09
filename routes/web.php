<?php

use App\Http\Controllers\Addresser;
use App\Http\Controllers\BoardingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\GroomingController;
use App\Http\Controllers\GroomingTypesController;

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

Route::view('/', 'welcome');

Auth::routes();

Route::middleware(['auth','crew'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/addresser', [Addresser::class, 'getAddreser'])->name('addresser');

    Route::get('/customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::get('/customer/generate_unique_id', [CustomerController::class, 'generateUniqueId'])->name('customer.generateUniqueId');
    Route::get('/customer/upgrade_to_member/{user}', [CustomerController::class, 'upgradeToMember'])->name('customer.upgradeToMember');
    Route::post('/customer/setFreegroomingManual/{user}', [CustomerController::class, 'setFreegroomingManual'])->name('customer.setFreegroomingManual');
    Route::resource('/customer', CustomerController::class);

    Route::get('/crew/search', [CrewController::class, 'search'])->name('crew.search');
    // Route::get('/crew/upgrade_to_member/{user}', [CrewController::class, 'upgradeToMember'])->name('crew.upgradeToMember');
    Route::resource('/crew', CrewController::class);

    Route::get('/cat/create_for/{user}', [CatController::class, 'createFor'])->name('cat.createFor');
    Route::post('/cat/store_for/{user}', [CatController::class, 'storeFor'])->name('cat.storeFor');
    Route::get('/cat/show_by/{user}', [CatController::class, 'showBy'])->name('cat.showBy');
    Route::resource('cat', CatController::class)->except('create');

    
    Route::get('/boarding/create/{user}', [BoardingController::class, 'create'])->name('boarding.create');
    Route::post('/boarding/store/{user}', [BoardingController::class, 'store'])->name('boarding.store');
    Route::resource('boarding', BoardingController::class)->except(['create','store']);
    
    Route::get('/delete/{idgrooming}', [GroomingController::class, "delete"])->name('grooming.delete');
    Route::get('/report_by/{user}', [GroomingController::class, "reportBy"])->name('grooming.reportBy');
    Route::get('/add_grooming/{user}', [GroomingController::class, "addGrooming"])->name('grooming.add');
    Route::get('/add_grooming_by_cat/{cat}', [GroomingController::class, "addGroomingByCat"])->name('grooming.addBycat');
    Route::post('/store_grooming/{user}', [GroomingController::class, "storeGrooming"])->name('grooming.store');
    Route::post('/store_grooming_bycat/{cat}', [GroomingController::class, "storeGroomingByCat"])->name('grooming.storeBycat');
    Route::get('/grooming_report', [GroomingController::class, "report"])->name('grooming.report');
    Route::get('/grooming/{idgrooming}/edit ', [GroomingController::class, "edit"])->name('grooming.edit');
    Route::put('/grooming/{idgrooming}', [GroomingController::class, "update"])->name('grooming.update');
    
    Route::resource('grooming_type', GroomingTypesController::class);
});

Route::view('/ui', 'ui');
