    @extends('layout.layout')

@section('content')
<form action="{{ route('user.tambah_data.formulir') }}" method="POST" class="card p-5">

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
        <label for="name" class="col-sm-2 col-form-label">Nama :</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="{{old('name') }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
        </div>
    </div>
    <div class="mb-3 row">
        <label for="email" class="col-sm-2 col-form-label">Email :</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="{{old('email') }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="password" class="col-sm-2 col-form-label">Password :</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="password" name="password" value="{{old('password') }}">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="type" class="col-sm-2 col-form-label">Tipe Pengguna:</label>
        <div class="col-sm-10">
            <select class="form-select" id="role" name="role">
                <option selected disabled hidden>Pilih</option>
                <option value="admin" {{old('role') == 'admin' ? 'selected' : ''}}>admin</option>
                <option value="cashier" {{old('role') ==  'cashier' ? 'selected' : ''}}>cashier</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Tambah Data</button>
</form>
@endsection
