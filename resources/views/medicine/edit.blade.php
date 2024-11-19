@extends('layout.layout')

@section('content')
    <h1 class="text-center mt-4">Halaman Edit Obat</h1>
    <div class="d-flex justify-content-center">
        <form action="{{ route('obat.data',medicine['id'])}}" method="POST" class="card p-5">
            @csrf
            @if(Session::get('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            <div class="mb-3 row">
                <label for="name" class="col-sm-2 col-form-label">Nama Obat :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $medicine['name']}}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="type" class="col-sm-2 col-form-label">Jenis Obat :</label>
                <div class="col-sm-10">
                    <select class="form-select" id="type" name="type">
                        <option selected disabled hidden>Pilih</option>
                        <option value="tablet" {{$medicine('type') == 'tablet' ? 'selected' : ''}}>tablet</option>
                        <option value="sirup" {{$medicine('type') == 'sirup' ? 'selected' : ''}}>sirup</option>
                        <option value="kapsul" {{$medicine('type') == 'kapsul' ? 'selected' : ''}}>kapsul</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="price" class="col-sm-2 col-form-label">Harga Obat :</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="price" name="price" value="{{$medicine('price') }}">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="stock" class="col-sm-2 col-form-label">Stok Tersedia :</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="stock" name="stock" value="{{$medicine('stock') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Tambah Data</button>
        </form>
    </div>
        @endsection
