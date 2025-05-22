<?php

use App\Http\Controllers\Api\ClientLogController;
use App\Http\Controllers\Api\HeadphoneStockController;
use App\Http\Controllers\Api\VisitorController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

    $user = User::where('email', $credentials['email'])->first();

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
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/visitors/search', [VisitorController::class, 'search']);
    Route::post('/visitors', [VisitorController::class, 'store']);
    Route::get('/visitors/{visitor}', [VisitorController::class, 'show']);
    Route::put('/visitors/{visitor}', [VisitorController::class, 'update']);
    Route::delete('/visitors/{id}', [VisitorController::class, 'destroy']);
    Route::get('/visitors/{visitor}/logs', [VisitorController::class, 'logs']);
    Route::get('/visitors/export', [VisitorController::class, 'export'])->middleware('role:admin');

    Route::get('/stock', [HeadphoneStockController::class, 'show']); //->middleware('role:admin');
    Route::put('/stock', [HeadphoneStockController::class, 'update']); //->middleware('role:admin');

    Route::get('/logs', [ClientLogController::class, 'index']);
    Route::post('/log-client-error', [ClientLogController::class, 'store']);
});
