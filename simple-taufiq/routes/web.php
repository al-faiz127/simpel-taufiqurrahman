<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Auth\Register;
use App\Models\Bangkom;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/admin/bangkom/{bangkom}/permohonan', function (Bangkom $bangkom) {
    $pdf = Pdf::loadView('pdf.permohonan', compact('bangkom'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('Permohonan-' . $bangkom->id . '.pdf');
})
->middleware(['auth'])
->name('bangkom.permohonan');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', Register::class)->name('register');
