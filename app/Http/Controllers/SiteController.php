<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::orderBy('nom')->get();
        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Site::create($data);
        return redirect()->route('sites.index')->with('success', 'Site créé.');
    }

    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        $data = $this->validateData($request);
        $site->update($data);
        return redirect()->route('sites.index')->with('success', 'Site modifié.');
    }

    public function destroy(Site $site)
    {
        // Empêche la suppression si des mouvements y sont liés
        $hasMouvements = $site->mouvementsSortants()->exists()
            || $site->mouvementsEntrants()->exists();

        if ($hasMouvements) {
            return back()->with('error', "Impossible : ce site est lié à des mouvements existants.");
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
                'actif'       => 'nullable|boolean',
            ]) + ['actif' => $request->boolean('actif', true)];
    }
}
