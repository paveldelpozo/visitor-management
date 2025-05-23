<?php

namespace App\Http\Controllers\Api;

use App\Events\HeadphonesChanged;
use App\Events\VisitorCreated;
use App\Events\VisitorDeleted;
use App\Events\VisitorUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorSearchRequest;
use App\Http\Requests\VisitorStoreRequest;
use App\Http\Requests\VisitorUpdateRequest;
use App\Models\Visitor;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class VisitorController extends Controller
{
    public function search(VisitorSearchRequest $request): \Illuminate\Http\JsonResponse
    {
        $query = Visitor::query();

        $request->get('search') && $query->search($request->get('search'));

//        foreach ($request->filters() as $field => $value) {
//            $query->{"search" . ucfirst($field)}($value);
//        }

        $results = $query;

        if ($results->get()->count() === 0) {
            return response()->json(['status' => 'not_found']);
        }

        if ($results->get()->count() === 1) {
            return response()->json([
                'status' => 'single',
                'visitor' => $results->get()->first()
            ]);
        }

        return response()->json([
            'status' => 'multiple',
            'visitors' => $results->latest()->limit(10)->get()
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

        broadcast(new VisitorCreated($visitor))->toOthers();

        return response()->json($visitor);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return response()->json(['message' => 'Visitor not found'], 404);
        }

        return response()->json($visitor);
    }

    public function update(VisitorUpdateRequest $request, Visitor $visitor): \Illuminate\Http\JsonResponse
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

        if ($cambio !== 0) {
            broadcast(new HeadphonesChanged($visitor, $cambio))->toOthers();
        }

        return response()->json($visitor);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $visitor = Visitor::find($id);

        if (! $visitor) {
            return response()->json(['message' => 'Visitor not found'], 404);
        }

        $visitorId = $visitor->id;

        $visitor->delete();

        broadcast(new VisitorDeleted($visitorId))->toOthers();

        return response()->json(['message' => 'Visitor deleted successfully']);
    }

    public function logs(Request $request, Visitor $visitor): \Illuminate\Http\JsonResponse
    {
        $logs = $visitor->logs()->with(['user', 'visitor']);

//        if ($request->has('search'))
//            $logs->when($request->input('search'), fn($q, $search) => $q->where('action', 'like', "%$search%"));
//
//        dd($logs->toSql(), $logs->getBindings());

        return response()->json($logs->latest()->paginate(10));
    }

    #[NoReturn] public function export(): void
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
