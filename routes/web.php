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

Route::get('/', function () {
    return redirect('/pets');
});

Route::get('/pets', function() {
    return view('pets.pets');
});

Route::get('/pets', function(){
    return view('pets.pets');
})->name('pets.index');

Route::post('/pets', [PetController::class, 'addPet'])->name('pets.addPet');

Route::post('/pets/find', [PetController::class, 'findPet'])->name('pets.findPet');

Route::delete('/pets/delete', [PetController::class, 'deletePet'])->name('pets.deletePet');

Route::put('/pets/edit', [PetController::class, 'editPet'])->name('pets.editPet');
