<?php

use App\Filament\Pages\Auth\Register;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Modal\HistoriTable;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', Register::class)->name('register');
