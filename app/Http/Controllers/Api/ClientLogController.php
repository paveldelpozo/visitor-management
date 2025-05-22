<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\VisitorLog;

class ClientLogController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = VisitorLog::with(['user', 'visitor']);

        if ($request->filled('user')) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->user . '%')
            );
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        return response()->json($query->latest()->paginate(25));
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar entrada básica
        $data = $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|array',
        ]);

        // Guardar en logs de Laravel (storage/logs/laravel.log)
        Log::channel('client')->error($data['message'], $data['context'] ?? []);

        return response()->json(['status' => 'logged']);
    }
}
