@extends('layout.layout')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-end"></div>

        <form action="" method="GET" class="form-inline mt-3">
            <div class="input-group">
                <input type="date" class="form-control" name="search_order" value="{{ request('search_order') }}"
                    placeholder="Cari berdasarkan tanggal">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Cari</button>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{route('pembelian.admin')}}" class="btn btn-primary">Clear</a>
                </div>
            </div>
        </form>

        <a href="{{route('pembelian.admin.export')}}" class="btn btn-success">Export Excel</a>
        <table class="table table-bordered table-stripped">
            <thead>
                <th>#</th>
                <td>Nama</td>
                <th>Obat</th>
                <th>Total Harga</th>
                <th>Nama Kasir</th>
                <th>Tanggal Pembelian</th>
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
                        <td>Rp.{{number_format($order->total_price,0,',','.') }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ \Carbon\Carbon::create($order->created_at)->locale('id')->translatedFormat('l, d F Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">{{ $orders->links()}}</div>
    </div>
@endsection
