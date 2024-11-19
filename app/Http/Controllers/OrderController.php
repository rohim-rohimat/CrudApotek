<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\medicine;
use App\Models\order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class OrderController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new OrderExport, 'rekap-pembelian.xlsx');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $searchDate = $request->input('search_order');

        // Query orders associated with the authenticated user, filtered by date if provided
        $orders = Order::with('user')
            ->where('user_id', $userId)
            ->when($searchDate, function ($query) use ($searchDate) {
                $query->whereDate('created_at', Carbon::parse($searchDate));
            })
            ->simplePaginate(5); // Adjust pagination as needed

        return view('order.kasir.kasir', compact('orders'));
    }

    public function indexAdmin(Request $request)
    {
        $orders = order::with('user')->where('created_at', 'LIKE', '%' . $request->search_order . '%')->simplePaginate(5);
        // dd($orders->toArray());
        return view('order.admin.data', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.form', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data request
        $request->validate(
            [
                "name_customer" => "required",
                "medicines" => "required"
            ]
        );

        // Mencari values array yang datanya sama
        $arrayValues = array_count_values($request->medicines);
        // Membuat array kosong untuk menampung nilai format array yang baru
        $arrayNewMedicines = [];
        // Looping array data duplikat
        foreach ($arrayValues as $key => $value) {
            // Mencari data dengan id yang sama
            $medicine = Medicine::where('id', $key)->first();

            if ($medicine['stock'] < $value) {
                $msg = 'Stock Obat' . $medicine['name'] . 'dengan stok' . $medicine['stock'] . ' Tidak Cukup, tidak dapat melakukan pembelian';
                redirect()->back()->with('failed',  $msg);
            }
            // else {
            //     $medicine['stock'] -= $value;
            //     $medicine->save();
            //  }

            // Untuk mentotalkan harga medicine
            $totalPrice = $medicine['price'] * $value;
            // Format array baru (strukturnya)
            $arrayItem = [
                "id" => $key,
                "name_medicine" => $medicine['name'],
                "qyt" => $value,
                "price" => $medicine['price'],
                "total_price" => $totalPrice
            ];

            array_push($arrayNewMedicines, $arrayItem);
        }

        // Untuk menghitung total
        $total = 0;
        // Looping data rray dari array format baru
        foreach ($arrayNewMedicines as $item) {
            // Mentotalkan price sebelum ppn dari medicine ke dalam variabel total
            $total += $item['total_price'];
        }

        // Merubah total dikali dengan ppn sebesar 10
        $ppn = $total + ($total * 0.1);

        // Tambahkan result ke dalam database order
        $orders = Order::create([
            'user_id' => Auth::user()->id,
            'medicines' => $arrayNewMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $ppn
        ]);

        if ($orders) {
            $result = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            return redirect()->route('pembelian.print', $result['id'])->with('success', "Berhasil Order");
        } else {
            return redirect()->back()->with('failed', "Gagal Order");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order, $id)
    {
        $order = Order::where('id', $id)->first();
        return view('order.kasir.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function downloadPDF($id)
    {
        $order = order::where('id', $id)->first()->toArray();
        view()->share('order', $order);
        $pdf = pdf::loadView('order.kasir.pdf', $order);
        return $pdf->download('struk-pembelian.pdf');
    }
}
