@extends('layout.layout')

@section('content')
        @if (Session::get('failed'))
        <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        <h1 class="display-4">
            Selamat Datang {{ Auth::user()->name }}
        </h1>
        <hr>
@endsection
