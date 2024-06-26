<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PieceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::get('/items', [ItemController::class, 'index'])->name('items.index');
// Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
// Route::post('/items', [ItemController::class, 'store'])->name('items.store');
// Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
// Route::patch('/items/{id}', [ItemController::class, 'update'])->name('items.update');
// Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
Route::get('/', function() {
	return redirect('/customers');
});

Route::resource('events', EventController::class);
Route::put('/events/{event}/addCustomer', [EventController::class, 'addCustomer'])->name('events.addCustomer');
Route::put('/events/{event}/removeCustomer', [EventController::class, 'removeCustomer'])->name('events.removeCustomer');
Route::put('/events/{event}/addItem', [EventItemController::class, 'create'])->name('events.addItem');
Route::put('/events/{event}/destroyItem', [EventItemController::class, 'destroy'])->name('events.destroyItem');
Route::put('/events/{event}/addItemPiece', [EventItemController::class, 'addPiece'])->name('events.addItemPiece');
Route::put('/events/{event}/addGroup', [EventItemController::class, 'addGroup'])->name('events.addGroup');
Route::put('/events/{event}/allocateBulkItem', [EventItemController::class, 'allocateBulkItem'])->name('events.allocateBulk');
Route::put('/events/{event}/actionBulk', [EventItemController::class, 'actionBulk'])->name('events.actionBulk');
Route::put('/events/{event}/removeItemPiece', [EventItemController::class, 'removePiece'])->name('events.removeItemPiece');
Route::put('/events/{event}/checkoutPiece', [EventItemController::class, 'checkoutPiece'])->name('events.checkoutPiece');
Route::put('/events/{event}/checkinPiece', [EventItemController::class, 'checkinPiece'])->name('events.checkinPiece');
Route::put('/events/{event}/completePiece', [EventItemController::class, 'completePiece'])->name('events.completePiece');

Route::resource('customers', CustomerController::class);
Route::put('/customers/{customer}/addEvent', [CustomerController::class, 'addEvent'])->name('customers.addEvent');

Route::resource('items', ItemController::class)->except(['destroy']);
Route::put('/items/{item}/destroyItem', [ItemController::class, 'destroy'])->name('items.destroy');
Route::put('/items/{item}/createPiece', [ItemController::class, 'createPiece'])->name('items.createPiece');
Route::delete('/items/{item}/destroyPiece', [ItemController::class, 'destroyPiece'])->name('items.destroyPiece');
Route::patch('/items/{item}/updatePiece', [ItemController::class, 'updatePiece'])->name('items.updatePiece');

Route::resource('categories', CategoryController::class)->except(['create']);
Route::put('/categories/{id}/addItem', [CategoryController::class, 'addItem'])->name('categories.addItem');
Route::put('/categories/{id}/removeItem', [CategoryController::class, 'removeItem'])->name('categories.removeItem');

Route::resource('groups', GroupController::class);
Route::put('/groups/{id}/addPiece', [GroupController::class, 'addPiece'])->name('groups.addPiece');
Route::put('/groups/{id}/removePiece', [GroupController::class, 'removePiece'])->name('groups.removePiece');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

Route::get('/test', [TestController::class, 'test'])->name('test');

require __DIR__.'/auth.php';
