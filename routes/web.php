<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

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

// Przekierowanie do formularzy

Route::get('/', function () {
    return redirect('/pets');
});

// Zwrócenie widoku

Route::get('/pets', function() {
    return view('pets.pets');
});

// Zwrócenie widoku z wynikiem formularzy

Route::get('/pets', function(){
    return view('pets.pets');
})->name('pets.index');

// Ścieżka do Kontrolera PetController i funkcji addPet()

Route::post('/pets', [PetController::class, 'addPet'])->name('pets.addPet');

// Ścieżka do Kontrolera PetController i funkcji findPet()

Route::post('/pets/find', [PetController::class, 'findPet'])->name('pets.findPet');

// Ścieżka do Kontrolera PetController i funkcji deletePet()

Route::delete('/pets/delete', [PetController::class, 'deletePet'])->name('pets.deletePet');

// Ścieżka do Kontrolera PetController i funkcji editPet()

Route::put('/pets/edit', [PetController::class, 'editPet'])->name('pets.editPet');
