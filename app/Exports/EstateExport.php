<?php

// app/Exports/EstateExport.php

namespace App\Exports;

use App\Models\Estate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Estate::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Province',
            'District',
            'Divisional Secretariat',
            'Grama Niladari Division',
            'Estate Name',
            'Plan No',
            'Land Extent',
            'Building Available',
            'Building Name',
            'Government Land',
            // Add more fields as needed
        ];
    }
}
