<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class ManualController extends Controller
{
    /**
     * Génère et télécharge le manuel utilisateur en PDF.
     */
    public function download()
    {
        $pdf = Pdf::loadView('manual.pdf');
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Manuel_Utilisateur_SUIVI-DEC_' . date('Y_m_d') . '.pdf');
    }
}
