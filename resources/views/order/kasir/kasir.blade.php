@extends('layout.layout')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-end">
            <a href="{{route('pembelian.formulir')}}" class="btn btn-primary">+ Tambah Pesanan</a>
        </div>

        <form action="{{ route('pembelian.order') }}" method="GET" class="form-inline mt-3">
            <div class="input-group">
                <input type="date" class="form-control" name="search_order" value="{{ request('search_order') }}"
                    placeholder="Cari berdasarkan tanggal">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{route('pembelian.order')}}" class="btn btn-primary">Clear</a>
                </div>
            </div>
        </form>

        <h1>DATA PEMBELIAN : {{ Auth::user()->name}}</h1>
        <table class="table table-bordered table-stripped">
            <thead>
                <th>#</th>
                <td>Nama</td>
                <th>Obat</th>
                <th>Total Harga</th>
                <th>Tanggal Pembelian</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($orders as $index => $order)
                    <tr>
                        <td>{{ ($orders->currentPage()-1) * ($orders->perPage()) + ($index+1) }}</td>
                        <td>{{$order->name_customer}}</td>
                        <td>
                            <ol>
                                @foreach ($order->medicines as $medicine)
                                    <li>{{$medicine['name_medicine']}} ( {{$medicine['qyt'] }} ) : Rp. {{number_format($medicine['total_price'],0,',','.') }}</li>
                                @endforeach
                            </ol>
                        </td>
                        <td>{{ $order->total_price}}</td>
                        <td>{{ \Carbon\Carbon::create($order->created_at)->locale('id')->translatedFormat('l, d F Y H:i:s') }}</td>
                        <td>
                            <a href="{{route('pembelian.download_pdf', $order->id)}}" class="btn btn-secondary">Cetak Struk</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">{{ $orders->links()}}</div>
    </div>
@endsection
