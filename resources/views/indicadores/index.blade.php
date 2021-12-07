@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<script src="{{ asset('js/chart.min.js') }}"></script>

<div style="text-align: center; margin:10px; padding: 10px">
<a href="{{ url('/indicadores/{compras}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Compras</a> 
<a href="{{ url('/indicadores/{ventas}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Ventas</a> 
<a href="{{ url('/indicadores/{entrefechas}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores entre fechas</a> 
<a href="{{ url('/hoy') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Hoy</a>
</div>
<br>

@switch($type)
    @case('{compras}')
        <x-indicadoresacumuladoscompras :indicadorC='$indicadorC' />
        @break
    @case('{ventas}')

    <x-indicadoracumuladosventas :indicadorV='$indicadorV' :ganancia='$ganancia'/>
        @break
    @case('{entrefechas}')

    <x-indicadorentrefechas :indicadorV='$indicadorV'/>
        @break
    @default
    <div style="text-align: center">Seleccione de que desea el indicador</div>
@endswitch

<br>
<script src="{{ asset('js/jquery.min.js') }}"></script>
@endsection