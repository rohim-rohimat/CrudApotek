<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('order')->orderBy('created_at', 'DESC')->get();
    }

    public function headings(): array
    {
        //membuat th
        return[
            "ID",
            "Nama",
            "Email",
            "Role"
        ];
    }

    public function map($user): array
    {
        return[
            $user->no,
            $user->name,
            $user->email,
            $user->role
        ];
    }
}
