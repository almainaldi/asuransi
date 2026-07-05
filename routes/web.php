<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

use App\Http\Controllers\ClaimController;

// Proteksi rute dengan middleware 'auth' (wajib login terlebih dahulu)
Route::middleware(['auth'])->group(function () {
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{id}/status', [ClaimController::class, 'updateStatus'])->name('claims.updateStatus');
});

// Pintu otomatis multi-role: http://127.0.0.1:8000/auto-login/{role}
Route::get('/auto-login/{role}', function ($role) {
    if (!in_array($role, ['user', 'verifier', 'approver'])) {
        return 'Role tidak valid! Pilih: user, verifier, atau approver.';
    }

    $user = User::where('role', $role)->first();

    if ($user) {
        Auth::login($user);
        return redirect('/claims');
    }

    return "Akun dengan role {$role} tidak ditemukan di database. Silakan jalankan 'php artisan db:seed'.";
});

Route::delete('/claims/{id}', [ClaimController::class, 'destroy'])->name('claims.destroy');
