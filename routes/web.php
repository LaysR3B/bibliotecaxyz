<!-- 

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
 -->
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (sin login)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/confirmacion', function () {
    return view('frontend.confirmacion');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| RUTAS DE ACCESO PROTEGIDAS (requieren login)
|--------------------------------------------------------------------------
*/

// Rutas protegidas - requieren autenticación
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $usuario = auth()->user();
        return view('frontend.dashboard', compact('usuario'));
    })->name('dashboard');

    Route::resource('libros', LibroController::class);

    Route::resource('prestamos', PrestamoController::class);

    Route::get('/reportes', [PrestamoController::class, 'reportes'])->name('reportes.index');

    Route::resource('usuarios', UsuarioController::class);
});

/*
|--------------------------------------------------------------------------
| PERFIL DE USUARIO (ya incluido por Breeze o Jetstream)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
