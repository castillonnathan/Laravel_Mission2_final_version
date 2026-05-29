<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Http\Requests\StoreMaterielRequest;
use App\Http\Requests\UpdateMaterielRequest;
use Illuminate\Http\Request;

class MaterielApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Materiel::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('alerte') && $request->alerte === 'oui') {
            $query->enAlerte();
        }

        $materiels = $query->latest('id_materiel')->paginate(10)->withQueryString();

        $stats = [
            'total'    => Materiel::count(),
            'alertes'  => Materiel::enAlerte()->count(),
            'ruptures' => Materiel::where('quantite_stock', 0)->count(),
            'stock'    => Materiel::sum('quantite_stock'),
        ];

        return response()->json([
            'data'  => $materiels,
            'stats' => $stats,
        ]);
    }

    public function store(StoreMaterielRequest $request)
    {
        $materiel = Materiel::create($request->validated());

        return response()->json($materiel, 201);
    }

    public function show(Materiel $materiel)
    {
        return response()->json($materiel);
    }

    public function update(UpdateMaterielRequest $request, Materiel $materiel)
    {
        $materiel->update($request->validated());

        return response()->json($materiel);
    }

    public function destroy(Materiel $materiel)
    {
        $materiel->delete();

        return response()->json(['message' => 'Matériel supprimé.'], 204);
    }
}
