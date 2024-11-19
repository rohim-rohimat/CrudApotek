<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //menentukan data yang akan dimunculkan di excel
        return Order::with('user')->orderBy('created_at', 'DESC')->get();
    }

    public function headings(): array
    {
        //membuat th
        return [
            "ID",
            "Nama Kasir",
            "Daftar Obat",
            "Nama Pembeli",
            "Total Harga",
            "Tanggal"
        ];
    }

    public function map($order): array
    {
        $daftarObat = "";
        foreach ($order->medicines as $key => $value) {
            $obat = $key + 1 . "." . $value['name_medicine'] . "(" . $value['qyt'] . " pcs) Rp. " . number_format($value['total_price'], 0, ',', '.') . " , ";
            //menggabungkan nilai di $daftarObat dengan string obat
            $daftarObat .= $obat;
        }
        return [
            $order->id,
            $order->user->name,
            $daftarObat,
            $order->name_customer,
            "Rp." . number_format($order->total_price, 0, ',', '.'),
            \Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Jakarta')->locale('id_ID')->translatedFormat('l, d F Y H:i:s')

        ];
    }
}
