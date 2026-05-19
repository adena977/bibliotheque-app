<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
   use App\Models\User;
use Illuminate\Support\Facades\Hash;
// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Routes protégées (authentification requise)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Catalogue livres (accessible à tous les membres connectés)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    // 👇 EMPRUNT PAR LE MEMBRE (AJOUTE ICI) 👇
     Route::post('/borrow/{id}', [BorrowingController::class, 'memberBorrow'])
        ->name('borrowings.member.store');
    // Mes emprunts (membre)
    Route::get('/my-borrowings', [BorrowingController::class, 'myBorrowings'])->name('my.borrowings');
    Route::post('/borrowings/{id}/extend', [BorrowingController::class, 'extend'])->name('borrowings.extend');
    
    // Mes réservations (membre)
    Route::get('/my-reservations', [ReservationController::class, 'myReservations'])->name('my.reservations');
    Route::post('/reservations/{book}/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    
    // Mes amendes (membre)
    Route::get('/my-fines', [FineController::class, 'index'])->name('fines.index');
});

// Routes pour bibliothécaire et admin (avec middleware role)
Route::middleware(['auth', 'role:librarian,admin'])->prefix('librarian')->name('librarian.')->group(function () {
    
    // Gestion livres (CRUD complet)
    Route::resource('books', App\Http\Controllers\Librarian\BookController::class);
    
    // Gestion emprunts (pour bibliothécaire)
    Route::get('/borrowings', [App\Http\Controllers\Librarian\BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [App\Http\Controllers\Librarian\BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [App\Http\Controllers\Librarian\BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{borrowing}', [App\Http\Controllers\Librarian\BorrowingController::class, 'show'])->name('borrowings.show');
    Route::post('/borrowings/{book}/{user}/return', [App\Http\Controllers\Librarian\BorrowingController::class, 'return'])->name('borrowings.return');
    
    // Gestion membres
    Route::get('/members', [App\Http\Controllers\Librarian\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [App\Http\Controllers\Librarian\MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [App\Http\Controllers\Librarian\MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [App\Http\Controllers\Librarian\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [App\Http\Controllers\Librarian\MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [App\Http\Controllers\Librarian\MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [App\Http\Controllers\Librarian\MemberController::class, 'destroy'])->name('members.destroy');
    Route::post('/members/{member}/toggle-status', [App\Http\Controllers\Librarian\MemberController::class, 'toggleStatus'])->name('members.toggle-status');
});

// Routes pour admin uniquement
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/borrowing-stats', [App\Http\Controllers\Admin\ReportController::class, 'borrowingStats'])->name('reports.borrowing');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::get('/settings/reset', [App\Http\Controllers\Admin\SettingsController::class, 'resetSettings'])->name('settings.reset');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gestion utilisateurs
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create'); // 👈 AJOUTER
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store'); // 👈 AJOUTER
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // ... autres routes
 

Route::get('/setup', function () {
    // Créer admin
    User::updateOrCreate(
        ['email' => 'admin@bibliotheque.com'],
        [
            'name' => 'Administrateur',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'membership_date' => now(),
        ]
    );
    
    // Créer bibliothécaire
    User::updateOrCreate(
        ['email' => 'librarian@bibliotheque.com'],
        [
            'name' => 'Bibliothécaire',
            'password' => Hash::make('lib123'),
            'role' => 'librarian',
            'is_active' => true,
            'membership_date' => now(),
        ]
    );
    
    // Créer membre
    User::updateOrCreate(
        ['email' => 'member@bibliotheque.com'],
        [
            'name' => 'Membre Test',
            'password' => Hash::make('member123'),
            'role' => 'member',
            'is_active' => true,
            'membership_date' => now(),
        ]
    );
    
    return "✅ Comptes créés avec succès !";
});
});