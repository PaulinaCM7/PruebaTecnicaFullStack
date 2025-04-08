<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return Inertia::render('Admin/Dashboard', [
                'auth' => ['user' => $user],
            ]);
        }
        return Inertia::render('MediaList', [
            'auth' => ['user' => $user],
        ]);
    })->name('dashboard');
    Route::get('/admin/media', fn () => Inertia::render('Admin/MediaIndex'))->name('admin.media');
    Route::get('/admin/reservas', fn () => Inertia::render('Admin/ReservationIndex'))->name('admin.reservas');
    Route::get('/admin/perfil/{id}', fn ($id) => Inertia::render('Admin/UserProfile', ['id' => $id]))->name('admin.perfil');
    Route::get('/admin/usuarios', fn () => Inertia::render('Admin/UserIndex'))->name('admin.usuarios');
    Route::get('/reservas', fn () => Inertia::render('ReservationHistory'))->name('reservas');
    Route::get('/medios/{id}', fn ($id) => Inertia::render('MediaDetail', ['id' => $id]))->name('medios.show');
});
