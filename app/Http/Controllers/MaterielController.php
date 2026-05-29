<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use App\Http\Requests\StoreMaterielRequest;
use App\Http\Requests\UpdateMaterielRequest;
use Illuminate\Http\Request;

class MaterielController extends Controller
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

        return view('materiels.index', compact('materiels', 'stats'));
    }

    public function create()
    {
        return view('materiels.create');
    }

    public function store(StoreMaterielRequest $request)
    {
        Materiel::create($request->validated());

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel ajouté avec succès.');
    }

    public function show(Materiel $materiel)
    {
        return view('materiels.show', compact('materiel'));
    }

    public function edit(Materiel $materiel)
    {
        return view('materiels.edit', compact('materiel'));
    }

    public function update(UpdateMaterielRequest $request, Materiel $materiel)
    {
        $materiel->update($request->validated());

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel mis à jour avec succès.');
    }

    public function destroy(Materiel $materiel)
    {
        $materiel->delete();

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel supprimé avec succès.');
    }
}
