<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicineExport;

class MedicineController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new MedicineExport, 'rekap-pembelian.xlsx');
    }
    /**
     * R: read, menampilkan banyak data/halaman awal fitur
     */
    public function index(Request $request)
    {
        //all() : mengambil semua data
        $orderStok = $request->sort_stock ? 'stock' : 'name';
        $medicines = Medicine::where('name', 'LIKE' , '%' .$request->search_obat . '%') ->orderBy($orderStok, 'ASC')->simplePaginate(5)->appends($request->all());
        //compact() : mengirim data ke view (isinya sama dengan $)
        return view('medicine.index', compact('medicines'));
    }

    /**
     * C : create, menampilkan form untuk menambahkan data
     */
    public function create()
    {
        //
        return view('medicine.create');
    }

    /**
     * C : create, menambahkan data ke db/eksekusi formulir
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' =>'required|max:100',
            'type' =>'required|min:3',
            'price' =>'required|numeric',
            'stock' =>'required|numeric',
        ],[
            'name.required' => 'Nama obat harus diisi!',
            'type.required' => 'Tipe obat harus diisi!',
            'price.required' => 'Harga obat harus diisi!',
            'stock.required' => 'Stok obat harus diisi!',
            'name.max' => 'Nama obat maksimal 100 karakter!',
            'type.min' => 'type obat minimal 3 karakter!',
            'price.numeric' => 'Harga obat harus berupa angka!',
            'stock.numeric' => 'Stok obat harus berupa angka!',
        ]);

        Medicine::create([
            'name'=> $request->name,
            'type'=> $request->type,
            'price'=> $request->price,
            'stock'=> $request->stock,
        ]);

        return redirect()->back()->with('success', 'Berhasil Menambahkan Data Obat');
    }

    /**
     * R : read, menampilkan data spesifik (data cuman 1)
     */
    public function show(string $id)
    {
        //
    }

    /**
     * U : update, menampilkan form untuk mengedit data
     */
    public function edit(string $id)
    {
        //
        $medicine = Medicine::where('id', $id)->first();
        return view('medicine.edit', compact('medicine'));
    }

    /**
     * U : update, mengupdate data ke db/eksekusi formulir edit
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'price' => 'required',
        ]);

        Medicine::where('id', $id)->update([
            'name' => '$request->name',
            'type' => '$request->type',
            'price' => '$request->price',
        ]);

        return redirect()->route('obat.data')->with('success', 'Berhasil mengupdate data');
    }

    public function updateStock(Request $request, $id){
        if (isset($request->stock) == FALSE) {
            $dataSebelumnya = Medicine::where('id', $id)->first();

            //kembali dengan pesan, id sebelumnya, dan stock sebelumnya (stock awal)
            return redirect()->back()->with([
                'failed' => 'Stock tidak boleh kosong!',
                'id' => $id,
                'stock' => $dataSebelumnya->stock,
            ]);
        }

        Medicine::where('id', $id)->update([
            'stock' => $request->stock,
        ]);

        return redirect()->back()->with('success', 'Berhasi mengupdate stock obat');
    }

    /**
     * D : delete, menghapus data dari db
     */
    public function destroy(string $id)
    {
        //
        $deleteData = Medicine::where('id', $id)->delete();

        if ($deleteData) {
            return redirect()->back()->with('success', 'Berhasil menghapus data obat');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data obat');
        }
    }

    public function downloadPDF($id)
    {
        $medicine = Medicine::where('id', $id)->first()->toArray();
        view()->share('medicine', $medicine);
        $pdf =pdf::loadView('order.kasir.pdf',$medicine);
        return $pdf->download('struk-pembelian.pdf');
    }
}
