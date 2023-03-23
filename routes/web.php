<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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

Route::post('/create-company',[CompanyController::class,'createCompany']);
Route::get('/company-details',[CompanyController::class,'getCompanyDetailsByID']);
Route::put('/update-company',[CompanyController::class,'updateCompany']);
Route::get('/get-companies-since-2001',[CompanyController::class,'getCompaniesSince2001TillNow']);
Route::get('/get-companies-by-activites',[CompanyController::class,'getCompaniesByActivites']);
Route::get('/set-db',[CompanyController::class,'setDB']);
