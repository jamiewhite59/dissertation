<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventItemController;
use App\Http\Controllers\ItemController;
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

Route::resource('events', EventController::class);
Route::put('/events/{event}/addCustomer', [EventController::class, 'addCustomer'])->name('events.addCustomer');
Route::put('/events/{event}/removeCustomer', [EventController::class, 'removeCustomer'])->name('events.removeCustomer');
Route::put('/events/{event}/addItem', [EventItemController::class, 'create'])->name('events.addItem');
Route::put('/events/{event}/destroyItem', [EventItemController::class, 'destroy'])->name('events.destroyItem');
Route::put('/events/{event}/addItemPiece', [EventItemController::class, 'addPiece'])->name('events.addItemPiece');
Route::put('/events/{event}/allocateBulkItem', [EventItemController::class, 'allocateBulkItem'])->name('events.allocateBulk');
Route::put('/events/{event}/removeItemPiece', [EventItemController::class, 'removePiece'])->name('events.removeItemPiece');
Route::put('/events/{event}/checkoutPiece', [EventItemController::class, 'checkoutPiece'])->name('events.checkoutPiece');
Route::put('/events/{event}/checkinPiece', [EventItemController::class, 'checkinPiece'])->name('events.checkinPiece');
Route::put('/events/{event}/completePiece', [EventItemController::class, 'completePiece'])->name('events.completePiece');

Route::resource('customers', CustomerController::class);

Route::resource('items', ItemController::class);
Route::put('/items/{item}/createPiece', [ItemController::class, 'createPiece'])->name('items.createPiece');
Route::delete('/items/{item}/destroyPiece', [ItemController::class, 'destroyPiece'])->name('items.destroyPiece');
Route::patch('/items/{item}/updatePiece', [ItemController::class, 'updatePiece'])->name('items.updatePiece');


Route::get('/test', [TestController::class, 'test'])->name('test');

require __DIR__.'/auth.php';
