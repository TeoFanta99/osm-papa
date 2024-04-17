<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClientServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', [MainController :: class, 'index']) -> name('welcome');
    Route::get('/consultants', [ConsultantController :: class, 'index']) -> name('index.consultants');
    Route::get('/clients', [ClientController :: class, 'index']) -> name('index.clients');
    Route::get('/invoices', [InvoiceController :: class, 'index']) -> name('index.invoices');
    Route::get('/create-newInvoice', [ClientServiceController :: class, 'create']) -> name('create.newInvoice');
    Route::post('/create-newInvoice', [ClientServiceController :: class, 'store']) -> name('store.invoice');
});

require __DIR__.'/auth.php';
