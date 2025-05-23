<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeadphoneStock;
use App\Models\Visitor;
use Illuminate\Http\Request;

class HeadphoneStockController extends Controller
{
    public function show(): \Illuminate\Http\JsonResponse
    {
        $stock = HeadphoneStock::firstOrFail();

        return response()->json([
            'total' => $stock->total,
            'used' => Visitor::sum('headphones'),
            'available' => HeadphoneStock::getAvailable()
        ]);
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'total' => 'required|integer|min:0|max:100'
        ]);

        $stock = HeadphoneStock::firstOrFail();
        $stock->update(['total' => $request->input('total')]);

        return response()->json($stock);
    }
}
