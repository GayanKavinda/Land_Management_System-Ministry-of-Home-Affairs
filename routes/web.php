<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\NonEstateController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserRequestController;


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


// Apply globally to all routes in the web middleware group
Route::middleware(['web', 'log.user.activity'])->group(function () {


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'can:manage-roles'])->group(function () {
    // Roles
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::resource('permissions', PermissionController::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //Assign roles to the users
    Route::post('/users/assign-role/{user}', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('users/{user}/remove-role', [UserController::class, 'removeRole'])->name('users.remove-role');
});

Route::middleware(['auth'])->group(function () {
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('permissions/{role}/assign-to-role', [PermissionController::class, 'assignToRole'])->name('permissions.assign-to-role');
    Route::get('roles/{role}/permissions', [PermissionController::class, 'show'])->name('permissions.show');
    Route::delete('roles/{role}/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    //Estates management allows for admin

    Route::get('/estate', [EstateController::class, 'index'])->name('estate');
    Route::post('/estate', [EstateController::class, 'store'])->name('addEstateData');
    Route::delete('/estates/{id}', [EstateController::class, 'destroy'])->name('deleteEstate');
    Route::get('/manage/estates', [EstateController::class, 'manageEstates'])->name('manageEstates');
    Route::put('/update/{id}', [EstateController::class, 'update'])->name('updateEstate');
    
    Route::post('/estate/move-to-acquired/{id}', [EstateController::class, 'moveToAcquired'])->name('estate.moveToAcquired');
    
    
});


Route::middleware(['auth'])->group(function () {
    Route::get('/viewEstate', [EstateController::class, 'viewEstate'])->name('showEstates');
    Route::get('/showData/{id}', [EstateController::class, 'showData'])->name('showData');
    Route::get('/filterResults', [EstateController::class, 'filterResults'])->name('filterResults');
    Route::get('/viewEstate', [EstateController::class, 'filterResults'])->name('showEstates');


    //newly added routes

    Route::post('/estate/nonAcEstates/create', [NonEstateController::class, 'store'])->name('estate.nonAcEstates.store');
    Route::get('/estate/nonAcEstates/create', [NonEstateController::class, 'create'])->name('estate.nonAcEstates.create');
    Route::get('/manage/nonestates', [NonEstateController::class, 'manageNonEstates'])->name('manageNonEstates');

    // Add the following routes for the edit and update actions
    Route::get('/estate/nonAcEstates/edit/{id}', [NonEstateController::class, 'edit'])->name('estate.nonAcEstates.edit');
    Route::put('/estate/nonAcEstates/update/{id}', [NonEstateController::class, 'update'])->name('estate.nonAcEstates.update');
    Route::delete('/estate/nonAcEstates/destroy/{id}', [NonEstateController::class, 'destroy'])->name('estate.nonAcEstates.destroy');

    //filter and View , defined same routes for both
    Route::get('/estate/nonAcEstates', [NonEstateController::class, 'view'])->name('estate.nonAcEstates');



    // routes/web.php
    Route::get('/get-districts-by-province-nonestate', [NonEstateController::class, 'getDistrictsByProvince'])->name('getDistrictsByProvince');
    Route::get('/get-divisional-secretariats-by-district-nonestate', [NonEstateController::class, 'getDivisionalSecretariatsByDistrict'])->name('getDivisionalSecretariatsByDistrict');
    Route::get('/get-grama-niladari-divisions-by-divisional-secretariat-nonestate', [NonEstateController::class, 'getGramaNiladariDivisionsByDivisionalSecretariat'])->name('getGramaNiladariDivisionsByDivisionalSecretariat');



    Route::get('/get-districts-by-province',  [EstateController::class, 'getDistrictsByProvince'])->name('getDistrictsByProvince');
    Route::get('/get-divisional-secretariats-by-district', [EstateController::class, 'getDivisionalSecretariatsByDistrict'])->name('getDivisionalSecretariatsByDistrict');
    Route::get('/get-grama-niladari-divisions-by-divisional-secretariat', [EstateController::class, 'getGramaNiladariDivisionsByDivisionalSecretariat'])->name('getGramaNiladariDivisionsByDivisionalSecretariat');

    //download estate data to excel
    Route::get('/export-estates', [EstateController::class, 'exportEstates'])->name('export.estate');
    Route::get('/show-reports', [EstateController::class, 'showReport'])->name('show.report');
    Route::get('/export-non-estates', [NonEstateController::class, 'exportNonEstates'])->name('export.non.estate');



    Route::get('/search-estates', [EstateController::class, 'search'])->name('search.estates');
    Route::get('/search-non-estates', [NonEstateController::class, 'search'])->name('search.nonEstates');

});

//Switch Language Route
Route::get('lang/{language}', [LanguageController::class, 'switchLanguage'])->name('lang.switch');

//emergen CCCCCCC
Route::get('/make-admin', [UserController::class, 'makeAdmin']);


Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs');

Route::post('estates/download-pdf/{searchQuery}', [EstateController::class, 'downloadAndProvidePdf'])->name('downloadAndProvidePdf');
Route::get('nonestates/download-pdf/{searchQuery}', [NonEstateController::class, 'downloadAndProvidePdf'])->name('downloadPdfNonEstates');

//User request routes
Route::post('/user-requests', [UserRequestController::class, 'store'])->name('user.requests.store');

});