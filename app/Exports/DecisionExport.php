<?php

namespace App\Exports;

use App\Models\Decision;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DecisionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;
    protected $status;

    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Decision::with(['type', 'session', 'institution']);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code / Référence',
            'Titre',
            'Type d\'acte',
            'Session associée',
            'Institution Chef de File',
            'Date d\'adoption',
            'Date d\'échéance',
            'Priorité',
            'Taux d\'avancement',
            'Statut'
        ];
    }

    public function map($decision): array
    {
        return [
            $decision->id,
            $decision->code,
            $decision->title,
            $decision->type ? $decision->type->name : 'N/A',
            $decision->session ? $decision->session->code : 'N/A',
            $decision->institution ? $decision->institution->name : 'N/A',
            $decision->date_adoption ? $decision->date_adoption->format('d/m/Y') : '',
            $decision->date_echeance ? $decision->date_echeance->format('d/m/Y') : '',
            $decision->priority == 1 ? 'Critique' : ($decision->priority == 2 ? 'Haute' : 'Moyenne'),
            $decision->progress_rate . '%',
            ucfirst($decision->status)
        ];
    }
}
