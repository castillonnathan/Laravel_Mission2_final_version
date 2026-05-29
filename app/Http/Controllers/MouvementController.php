<?php

namespace App\Http\Controllers;

use App\Models\Minerai;
use App\Models\Mouvement;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $query = Mouvement::with(['minerai', 'siteSource', 'siteDestination', 'user'])
            ->latest('date_mouvement');

        // Filtres optionnels
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }
        if ($mineraiId = $request->query('minerai_id')) {
            $query->where('minerai_id', $mineraiId);
        }
        if ($siteId = $request->query('site_id')) {
            $query->where(function ($q) use ($siteId) {
                $q->where('site_source_id', $siteId)
                    ->orWhere('site_destination_id', $siteId);
            });
        }

        $mouvements = $query->paginate(25)->withQueryString();
        $minerais   = Minerai::orderBy('nom')->get();
        $sites      = Site::orderBy('nom')->get();

        return view('mouvements.index', compact('mouvements', 'minerais', 'sites'));
    }

    public function create(Request $request)
    {
        $type     = $request->query('type', Mouvement::TYPE_ENTREE);
        $minerais = Minerai::where('actif', true)->orderBy('nom')->get();
        $sites    = Site::where('actif', true)->orderBy('nom')->get();

        return view('mouvements.create', compact('type', 'minerais', 'sites'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'                => ['required', Rule::in(array_keys(Mouvement::TYPES))],
            'minerai_id'          => 'required|exists:minerais,id',
            'quantite'            => 'required|numeric',
            'site_source_id'      => 'nullable|exists:sites,id',
            'site_destination_id' => 'nullable|exists:sites,id',
            'motif'               => 'nullable|string',
            'date_mouvement'      => 'nullable|date',
        ]);

        try {
            $mouvement = Mouvement::create($data);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('mouvements.show', $mouvement)
            ->with('success', "Mouvement {$mouvement->numero} enregistré.");
    }

    public function show(Mouvement $mouvement)
    {
        $mouvement->load(['minerai', 'siteSource', 'siteDestination', 'user']);
        return view('mouvements.show', compact('mouvement'));
    }

    public function destroy(Mouvement $mouvement)
    {
        // Seuls les admins peuvent supprimer un mouvement (gère ça via middleware ou ici)
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Seul un administrateur peut supprimer un mouvement.');
        }

        $numero = $mouvement->numero;
        $mouvement->delete(); // l'observer s'occupe d'inverser le stock

        return redirect()
            ->route('mouvements.index')
            ->with('success', "Mouvement {$numero} supprimé, stock recalculé.");
    }
}
