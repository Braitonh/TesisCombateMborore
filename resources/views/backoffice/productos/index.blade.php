@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Lista de Productos</h1>
        @if (session()->has('message'))
            <div class="bg-green-500 text-white p-2 rounded-md mb-4">
                {{ session('message') }}
            </div>
        @endif
        @include('backoffice.productos.table', ['productos' => $productos])
    </div>
@endsection