<?php

namespace App\Http\Controllers;

use App\Models\Minerai;
use App\Models\Site;
use App\Models\StockMinerai;

class StockController extends Controller
{
    public function index()
    {
        $sites    = Site::where('actif', true)->orderBy('nom')->get();
        $minerais = Minerai::where('actif', true)->orderBy('nom')->get();

        // Construit une matrice [site_id][minerai_id] => quantité
        $stocks = StockMinerai::all();
        $matrice = [];
        foreach ($stocks as $s) {
            $matrice[$s->site_id][$s->minerai_id] = (float) $s->quantite;
        }

        // Totaux par minerai (tous sites confondus)
        $totauxParMinerai = [];
        foreach ($minerais as $m) {
            $totauxParMinerai[$m->id] = 0;
            foreach ($sites as $s) {
                $totauxParMinerai[$m->id] += $matrice[$s->id][$m->id] ?? 0;
            }
        }

        return view('stocks.index', compact('sites', 'minerais', 'matrice', 'totauxParMinerai'));
    }
}
