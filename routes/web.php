<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ResultByStationController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\ResultByMaterialController;
use App\Http\Controllers\ResultByMaterialCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the 'web' middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/change_datetime', [AuthController::class, 'changeDatetime'])->name('change_datetime');
Route::get('/', DashboardController::class)->name('dashboard')->middleware(['auth']);
Route::get('/setup', [SetupController::class, 'index'])->name('setup.index')->middleware(['auth', 'check.permission']);
Route::put('/setup/{id}', [SetupController::class, 'update'])->name('setup.update')->middleware(['auth', 'check.permission']);
Route::resource('/role', RoleController::class)->middleware(['auth', 'check.permission']);
Route::resource('/user', UserController::class)->middleware(['auth', 'check.permission']);
Route::get('/activity_log', ActivityLogController::class)->name('activity_log')->middleware(['auth', 'check.permission']);
Route::resource('/station', StationController::class)->middleware(['auth', 'check.permission']);
Route::resource('/material_category', MaterialCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/measurement_unit', MeasurementUnitController::class)->middleware(['auth', 'check.permission']);
Route::resource('/option', OptionController::class)->middleware(['auth', 'check.permission']);
Route::resource('/parameter', ParameterController::class)->middleware(['auth', 'check.permission']);
Route::resource('/material', MaterialController::class)->middleware(['auth', 'check.permission']);
Route::resource('/analysis', AnalysisController::class)->middleware(['auth', 'check.permission']);
Route::get('/result_by_material/{material_id}', [ResultByMaterialController::class, 'index'])->name('result_by_material.index')->middleware(['auth', 'check.permission']);
Route::get('/result_by_station/{station_id}', [ResultByStationController::class, 'index'])->name('result_by_station.index')->middleware(['auth', 'check.permission']);
Route::post('/result_by_station', [ResultByStationController::class, 'filter'])->name('result_by_station.filter');
Route::get('/result_by_material_category/{material_category_id}', [ResultByMaterialCategoryController::class, 'index'])->name('result_by_material_category.index')->middleware(['auth', 'check.permission']);
Route::post('/result_by_material_category', [ResultByMaterialCategoryController::class, 'filter'])->name('result_by_material_category.filter');


