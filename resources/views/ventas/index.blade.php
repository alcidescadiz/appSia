@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">
    <div>
        <h2><i>Registro de ventas</i></h2>
        <form action="{{URL::to('/exportv')}}" method="post">
            {!! csrf_field() !!}
            <button type="submit" class='btn btn-info'>Exportar Ventas a Excel</button>
        </form>
        <br>
        <form action="{{URL::to('/exportdetallesv')}}" method="post">
            {!! csrf_field() !!}
            <button type="submit" class='btn btn-info'>Exportar Detalles de Ventas a Excel</button>
        </form>
    </div>
    <br>
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }} 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    <br>
    
    <table class="table table-striped table-inverse">
        <thead class="thead-inverse">
            <tr> 
                <th>ID</th>
                <th>FECHA</th>
                <th>CLIENTE</th>
                <th>TIPO DE PAGO</th>
                <th>TOTAL IVA</th>
                <th>SUB TOTAL</th>
                <th>TOTAL</th>
                <th colspan="3" style="text-align: center">ACCIONES</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($ventas as $item)
                <tr>
                    <td data-titulo="N° de venta" scope="row">{{$item->id}}</td>
                    <td data-titulo="Fecha">{{$item->fecha}}</td>
                    <td data-titulo="Cliente:">{{$item->nombre}}</td>
                    <td data-titulo="Tipo de pago:">{{$item->tipo}}</td>
                    <td data-titulo="Iva: ">{{$item->total_iva}}</td>
                    <td data-titulo="Subtotal">{{$item->subtotal}}</td>
                    <td data-titulo="Total">{{$item->total}}</td>
                    <td style="text-align: center">
                        <form method="POST" action="{{ url("ventase/{$item->id}") }}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-primary" type="submit">Editar</button>
                        </form>
                    </td>
                    <td style="text-align: center">
                        <form method="POST" action="{{ url("ventas/{$item->id}/edit") }}">
                            @csrf
                            @method('DELETE')
                            <x-eliminar :ide="$item->id" />
                        </form>
                    </td>
                    <td style="text-align: center">
                        <a  href="print/{{$item->id}}" target="_blank" class="btn btn-secondary">Imprimir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No hay Ventas registradas</td>
                </tr>
                @endforelse
            </tbody>
    </table>
    {{$ventas->links()}}
 
</div>
@endsection