<?php

use App\Http\Controllers\Api\ClientLogController;
use App\Http\Controllers\Api\HeadphoneStockController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VisitorController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//Route::middleware('guest')->post('/login', function (Request $request) {
//    $credentials = $request->validate([
//        'email' => ['required', 'email'],
//        'password' => ['required'],
//    ]);
//
//    if (!Auth::attempt($credentials)) {
//        return response()->json(['message' => 'Credenciales incorrectas'], 401);
//    }
//
//    return response()->json($request->user());
//});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->with('roles')->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user,
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Logged out']);
});

Route::get('/user', function (Request $request) {
    return Auth::user()->load('roles');
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('visitors')->group(function () {
    Route::get('/search', [VisitorController::class, 'search']);
    Route::get('/export', [VisitorController::class, 'export']); //->middleware('role:admin');
    Route::get('', [VisitorController::class, 'index']);
    Route::post('', [VisitorController::class, 'store']);
    Route::get('/{visitor}', [VisitorController::class, 'show']);
    Route::put('/{visitor}', [VisitorController::class, 'update']);
    Route::delete('/{id}', [VisitorController::class, 'destroy']);
    Route::get('/{visitor}/logs', [VisitorController::class, 'logs']);
});

Route::middleware('auth:sanctum')->prefix('stock')->group(function () {
    Route::get('/', [HeadphoneStockController::class, 'show']);
    Route::put('/', [HeadphoneStockController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logs', [ClientLogController::class, 'index']);
    Route::post('/log-client-error', [ClientLogController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); //->middleware('role:admin');
    Route::post('/', [UserController::class, 'store']); //->middleware('role:admin');
    Route::get('{user}', [UserController::class, 'show']); //->middleware('role:admin');
    Route::put('{user}', [UserController::class, 'update']); //->middleware('role:admin');
    Route::delete('{user}', [UserController::class, 'destroy']); //->middleware('role:admin');
});
