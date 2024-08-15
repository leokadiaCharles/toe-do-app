<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
use App\Models\User;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $tasks = Task::orderBy('completed')->get();
    $users = User::all();

    return view('dashboard', compact('tasks', 'users'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tasks/index', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::resource('tasks', TaskController::class);


Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

Route::middleware('auth')->group(function () {
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    // Other routes that require authentication
});


Route::get('/debug-user', function () {
    $user = auth()->user();
    return response()->json([
        'user' => $user,
        'user_id' => $user ? $user->id : 'No user authenticated'
    ]);
});




require __DIR__.'/auth.php';
