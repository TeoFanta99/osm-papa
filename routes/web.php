<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\ServiceSoldController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\NoteController;


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
    Route::get('/index/profile', [ProfileController::class, 'index'])->name('index.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', [MainController :: class, 'index']) -> name('welcome');
    Route::get('/myarea', [AreaController :: class, 'index']) -> name('index.area');
    
    Route::get('/consultants', [ConsultantController :: class, 'index']) -> name('index.consultants');
    Route::get('/create-newConsultant', [ConsultantController :: class, 'create']) -> name('create.consultant');
    Route::post('/create-newConsultant', [ConsultantController :: class, 'store']) -> name('store.consultant');
    Route::get('/clients', [ClientController :: class, 'index']) -> name('index.clients');
    Route::get('/invoices', [InvoiceController :: class, 'index']) -> name('index.invoices');
    Route::get('/consultant/{id}', [ConsultantController :: class, 'show']) -> name('show.consultant');

    Route::get('/create-newInvoice', [InvoiceController :: class, 'create']) -> name('create.newInvoice');
    Route::get('/edit-installments/{clientServiceId}/edit', [InstallmentController :: class, 'edit']) -> name('edit.installments');
    Route::post('/create-newInvoice', [InvoiceController :: class, 'store']) -> name('store.invoice');

    Route::get('/invoice/{id}', [InvoiceController :: class, 'show']) -> name('show.invoice');
    Route::put('/invoice/{invoiceId}/update', [InvoiceController::class, 'update'])->name('update.invoice');

    Route::put('/update-installments/{invoice}', [InstallmentController::class, 'update'])->name('update.installments');

    Route::get('/commissions/{installmentId}/edit', [CommissionController :: class, 'edit']) -> name('edit.commissions');
    Route::put('/commissions/{installmentId}/update', [CommissionController::class, 'update'])->name('update.commissions');

    Route::get('/notes', [NoteController :: class, 'index']) -> name('index.notes');
    Route::get('/create/note', [NoteController :: class, 'create']) -> name('create.note');
    Route::get('/note/{noteId}/edit', [NoteController :: class, 'edit']) -> name('edit.note');
    Route::post('/create/note', [NoteController :: class, 'store']) -> name('store.note');
    Route::put('/note/{noteId}/update', [NoteController::class, 'update'])->name('update.note');
    ROute::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('delete.note');
    

});


require __DIR__.'/auth.php';
