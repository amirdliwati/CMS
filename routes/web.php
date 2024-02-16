<?php

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

// First line of Route
Auth::routes();

require __DIR__.'/routes_web/auth.php';
require __DIR__.'/routes_web/admin.php';
require __DIR__.'/routes_web/financial.php';
require __DIR__.'/routes_web/hr.php';
require __DIR__.'/routes_web/inventory.php';
require __DIR__.'/routes_web/mutual.php';

// Last line of Route
Route::any('{query}', function() { return redirect('/'); })->where('query', '.*');
