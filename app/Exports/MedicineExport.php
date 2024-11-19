<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MedicineExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Medicine::orderBy('created_at', 'DESC')->get();
    }

    public function headings(): array
    {
        //membuat th
        return [
            "ID",
            "Nama Obat",
            "Tipe",
            "Harga",
            "Stok ",
        ];
    }

    public function map($medicine): array
    {
        return[
            $medicine->no,
            $medicine->name,
            $medicine->type,
            $medicine->price,
            $medicine->stock
        ];
    }
}
