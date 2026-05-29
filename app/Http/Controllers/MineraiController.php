<?php

namespace App\Http\Controllers;

use App\Models\Minerai;
use Illuminate\Http\Request;

class MineraiController extends Controller
{
    public function index()
    {
        $minerais = Minerai::orderBy('nom')->get();
        return view('minerais.index', compact('minerais'));
    }

    public function create()
    {
        return view('minerais.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Minerai::create($data);
        return redirect()->route('minerais.index')->with('success', 'Minerai créé.');
    }

    public function edit(Minerai $minerai)
    {
        return view('minerais.edit', compact('minerai'));
    }

    public function update(Request $request, Minerai $minerai)
    {
        $data = $this->validateData($request, $minerai->id);
        $minerai->update($data);
        return redirect()->route('minerais.index')->with('success', 'Minerai modifié.');
    }

    public function destroy(Minerai $minerai)
    {
        if ($minerai->mouvements()->exists()) {
            return back()->with('error', "Impossible : ce minerai est lié à des mouvements existants.");
        }

        $minerai->delete();
        return redirect()->route('minerais.index')->with('success', 'Minerai supprimé.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        $unique = 'unique:minerais,code';
        if ($ignoreId) {
            $unique .= ',' . $ignoreId;
        }

        return $request->validate([
                'code'        => "required|string|max:20|{$unique}",
                'nom'         => 'required|string|max:255',
                'unite'       => 'required|string|max:20',
                'description' => 'nullable|string',
                'actif'       => 'nullable|boolean',
            ]) + ['actif' => $request->boolean('actif', true)];
    }
}
