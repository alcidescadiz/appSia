@extends('layouts.app')
@section('title', __('Dashboard'))


@section('content')

<div class="container">
    
    @if (session()->has('message'))
        <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
            {{ session('message') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <x-tablas.buscar :tablas="$tablas"  :nombre="$nombre" :database="$database" />

    <x-tablas.tabla :tablas="$tablas" :nombre="$nombre" :database="$database" :header="$header" :body="$body" :campos="$campos"  />

</div>

@endsection
