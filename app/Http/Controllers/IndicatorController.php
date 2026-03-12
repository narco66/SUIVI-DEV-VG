<?php

namespace App\Http\Controllers;

use App\Models\Indicator;
use App\Http\Requests\IndicatorRequest;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    public function store(IndicatorRequest $request)
    {
        $indicator = Indicator::create($request->validated());

        return redirect()->back()
            ->with('success', 'Indicateur ajouté avec succès.');
    }

    public function update(Request $request, Indicator $indicator)
    {
        $request->validate([
            'current_value' => 'required|numeric'
        ]);

        $indicator->update([
            'current_value' => $request->current_value
        ]);

        return redirect()->back()
            ->with('success', 'Valeur de l\'indicateur mise à jour.');
    }

    public function destroy(Indicator $indicator)
    {
        $indicator->delete();

        return redirect()->back()
            ->with('success', 'Indicateur supprimé avec succès.');
    }
}
