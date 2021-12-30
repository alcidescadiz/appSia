@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">

    
@if (session()->has('message_tabla'))
<div class="alert  {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
    <p style="width: 90%;  overflow: hidden;">{{ session('message_tabla') }} </p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

    <x-tablas.buscar :tablas="$tablas" />

</div>
@endsection