<?php


// app/Exports/ExibitionVisitorExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExibitionVisitorExport implements FromCollection, WithHeadings
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Event Venue',
            'Jeweller Name',
            'Owner Name',
            'Email',
            'Mobile 1',
            'Mobile 2',
            'Address',
            'City',
            'Enquired For',
            'Headway Service',
            'Remarks',
            'Created At'
        ];
    }
}
