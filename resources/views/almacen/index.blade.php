@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container" style=" overflow: auto" >
    <div>
        <h2><i>Detalles de Almacen</i></h2>
    </div>
    <br>
    
    <table class="table table-striped" style="margin:5px; padding: 5px; width: 95%">
        <thead class="thead-inverse">
            <tr> 
                <th>CODIGO</th>
                <th>NOMBRE</th>
                <th>ESTATUS</th>
                <th>CANTIDAD COMPRADAS</th>
                <th>CANTIDAD VENDIDAS</th>
                <th>EXISTENCIAS</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($almacen as $item)
                <tr style="background-color: #{{$item->color}}">
                    <td data-titulo="CODIGO">{{$item->codigo}}</td>
                    <td data-titulo="NOMBRE">{{$item->nombre}}</td>
                    <td data-titulo="ESTATUS">{{$item->estatus}}</td>
                    <td data-titulo="CANTIDAD COMPRADAS">{{$item->compras}}</td>
                    <td data-titulo="CANTIDAD VENDIDAS">{{$item->ventas}}</td>
                    <td data-titulo="EXISTENCIAS">{{$item->existencias}}</td>
                    <td data-titulo="ACCIONES">{{$item->acciones}}</td>
                </tr>
                @empty
                <tr>
                    <th colspan="7">No hay registros de compras de productos</th>
                </tr>
                @endforelse
            </tbody>
    </table>

</div>
@endsection