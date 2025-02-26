@extends('layout/layout')

@section('content')
    <form action="{{ route('login.proses') }}" class="card p-5" method="POST">
        @csrf
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if (Session::get('success'))
            <div class="alert alert-danger">{{ Session::get('success') }}</div>
        @endif
        {{-- @if (Session::get('logout'))
            <div class="alert alert-primary">{{ Session::get('logout') }}</div>
        @endif --}}
        {{-- @if (Session::get('canAccess'))
            <div class="alert alert-danger">{{ Session::get('canAccess') }}</div>
        @endif --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control">
            {{-- @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror --}}
        </div>
        <div class="mb-3">
            <label for="paswword" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            {{-- @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror --}}
        </div>
        <button type="submit" class="btn btn-success">LOGIN</button>
    </form>

@endsection
