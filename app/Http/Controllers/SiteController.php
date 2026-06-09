<?php

namespace App\Http\Controllers;

use App\Models\Minerai;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::with('mineraisAutorises')->orderBy('nom')->get();
        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        $minerais = Minerai::where('actif', true)->orderBy('nom')->get();
        return view('sites.create', compact('minerais'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $site = Site::create($data);

        // Sync des minerais exploitables (uniquement pertinent pour les mines)
        if ($site->estUneMine()) {
            $site->mineraisAutorises()->sync($request->input('minerais_autorises', []));
        }

        return redirect()->route('sites.index')->with('success', 'Site créé.');
    }

    public function edit(Site $site)
    {
        $minerais = Minerai::where('actif', true)->orderBy('nom')->get();
        $site->load('mineraisAutorises');
        return view('sites.edit', compact('site', 'minerais'));
    }

    public function update(Request $request, Site $site)
    {
        $oldType = $site->type;
        $data    = $this->validateData($request);
        $site->update($data);

        if ($site->estUneMine()) {
            $site->mineraisAutorises()->sync($request->input('minerais_autorises', []));
        } else {
            // Si le site n'est plus une mine, on efface les restrictions
            if ($oldType === Site::TYPE_MINE) {
                $site->mineraisAutorises()->detach();
            }
        }

        return redirect()->route('sites.index')->with('success', 'Site modifié.');
    }

    public function destroy(Site $site)
    {
        $hasMouvements = $site->mouvementsSortants()->exists()
            || $site->mouvementsEntrants()->exists();

        if ($hasMouvements) {
            return back()->with('error', 'Impossible : ce site est lié à des mouvements existants.');
        }

        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site supprimé.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'nom'         => 'required|string|max:255',
            'type'        => 'required|in:mine,depot,client_site,autre',
            'adresse'     => 'nullable|string|max:255',
            'ville'       => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'pays'        => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
            'minerais_autorises' => 'required|array| min:1',
            'actif'       => 'nullable|boolean',
        ]) + ['actif' => $request->boolean('actif', true)];
    }
}
