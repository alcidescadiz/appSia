@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')

<div class="container">

<h2>Cuentas por cobrar:</h2>
<br>
<table class="table table-striped table-inverse">
    <thead class="thead-inverse">
        <tr>
            <th>ID</th>
            <th>FECHA</th>
            <th>CLIENTE</th>
            <th>TIPO DE COMPRA</th>
            <th>MONTO A PAGAR</th>
            <th>ESTATUS</th>
            <th>FECHA A PAGAR</th>
            <th style="text-align: center">ACCIONES</th>
        </tr>
        </thead>
        <tbody>
            @forelse ($cobrar as $item)
            <tr style="background-color: #{{$item->color}}">
                <td data-titulo="Id">{{$item->id}}</td>
                <td data-titulo="Fecha de compra" scope="row">{{$item->fecha}}</td>
                <td data-titulo="Cliente">{{$item->nombre}}</td>
                <td data-titulo="Tipo de pago">{{$item->tipo}}</td>
                <td data-titulo="Monto">{{$item->total}}</td>
                <td data-titulo="Estatus">{{$item->estatus}}</td>
                <td data-titulo="Fecha a cobrar">{{$item->fecha_a_pagar}}</td>
                <td style="text-align: center">
                    <form action="{{ url('cobrar/'.$item->idc.'/edit')}}" method="post">
                        {!! csrf_field() !!} 
                        {{ method_field('PUT')}}
                        <input name="" id="" class="btn btn-primary" type="submit" value="Confirmar pago">
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <th colspan="8">No hay cuentas por cobrar pendiente por verificar</th>
            </tr>
            @endforelse
        </tbody>
</table>




</div>


@endsection