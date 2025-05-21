<?php

namespace App\Http\Controllers\Api;

use App\Events\VisitorUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorSearchRequest;
use App\Http\Requests\VisitorStoreRequest;
use App\Http\Requests\VisitorUpdateRequest;
use App\Models\Visitor;
use App\Models\VisitorLog;

class VisitorController extends Controller
{
    public function search(VisitorSearchRequest $request): \Illuminate\Http\JsonResponse
    {
        $query = Visitor::query();
        foreach ($request->filters() as $field => $value) {
            $query->{"search" . ucfirst($field)}($value);
        }

        $results = $query->get();

        if ($results->count() === 0) {
            return response()->json(['status' => 'not_found']);
        }

        if ($results->count() === 1) {
            return response()->json([
                'status' => 'single',
                'visitor' => $results->first()
            ]);
        }

        return response()->json([
            'status' => 'multiple',
            'visitors' => $results
        ]);
    }

    public function store(VisitorStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $visitor = Visitor::create($request->validated());
        VisitorLog::create([
            'visitor_id' => $visitor->id,
            'user_id' => auth()->id(),
            'action' => 'Creado con ' . $visitor->headphones . ' auriculares'
        ]);

        broadcast(new VisitorUpdated($visitor))->toOthers();

        return response()->json($visitor);
    }

    public function update(VisitorUpdateRequest $request, Visitor $visitor)
    {
        $original = $visitor->headphones;
        $visitor->update($request->validated());

        $cambio = $visitor->headphones - $original;
        VisitorLog::create([
            'visitor_id' => $visitor->id,
            'user_id' => auth()->id(),
            'action' => 'Actualizado, cambio auriculares: ' . ($cambio >= 0 ? "+$cambio" : $cambio)
        ]);

        broadcast(new VisitorUpdated($visitor))->toOthers();

        return response()->json($visitor);
    }

    public function logs(Request $request, Visitor $visitor)
    {
        $logs = $visitor->logs()->latest()
            ->when($request->input('search'), fn($q, $search) => $q->where('action', 'like', "%$search%"))
            ->paginate(10);

        return response()->json($logs);
    }

    public function export()
    {
        $csv = Visitor::all()->map(fn($v) => [
            'Nombre' => $v->name,
            'Apellidos' => $v->surname,
            'Teléfono' => $v->phone,
            'Auriculares' => $v->headphones,
        ]);

        $filename = 'visitors_export_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        fputcsv($handle, array_keys($csv->first()));
        foreach ($csv as $line) fputcsv($handle, $line->toArray());

        fclose($handle);
        exit;
    }
}
