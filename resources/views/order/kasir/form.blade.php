@extends('layout.layout')
@section('content')
    <div class="container mt-3">
        <form action="{{ route('pembelian.store.order') }}" class="card m-auto p-5" method="POST">
            @csrf

            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif

            <p>Penanggung Jawab : <b>{{ Auth::user()->name }}</b></p>

            <div class="mb-3 row">
                <label for="name_customer" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name_customer" name="name_customer">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="medicines" class="col-sm-2 col-form-label">Obat</label>
                <div class="col-sm-10">
                    <div class="d-flex align-items-center">
                        <select name="medicines[]" id="medicines" class="form-select">
                            <option selected hidden disabled>---Pilih Obat---</option>
                            @foreach ($medicines as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }} - Stok: {{ $item['stock']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="wrap-medicines"></div>
                    <br>
                    <p style="cursor: pointer;" class="text-success" id="add-select">+ Add Obat</p>
                </div>
            </div>
            <button type="submit" class="btn btn-block btn-lg btn-success">Konfirmasi Pembelian</button>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        let no = 2;

        $("#add-select").on("click", function() {
            let html = `<div class="medicine-group d-flex align-items-center mb-2">
                            <select name="medicines[]" class="form-select" required>
                                <option selected hidden disabled>Pesanan ${no}</option>
                                @foreach ($medicines as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }} - Stok: {{ $item['stock']}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="remove-select btn btn-outline-danger btn-sm ms-2">
                                <i class="fas fa-times">x</i>
                            </button>
                        </div>`;
            $("#wrap-medicines").append(html);
            no++;
        });

        $(document).on("click", ".remove-select", function() {
            $(this).closest(".medicine-group").remove();
            no--;
        });
    </script>
@endpush
