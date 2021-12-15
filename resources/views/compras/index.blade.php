@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')



<div class="container">
    <div>
        <h2><i>Registro de compras</i></h2>
        <form action="{{URL::to('/export')}}" method="post">
            {!! csrf_field() !!}
            <button type="submit" class='btn btn-info'>Exportar Compras a Excel</button>
        </form>
        <br>
        <form action="{{URL::to('/exportdetalles')}}" method="post">
            {!! csrf_field() !!}
            <button type="submit" class='btn btn-info'>Exportar Detalles de Compras a Excel</button>
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
</div>
    <br>

<div id="tablacopras" class="container">
    <table class="table table-striped">
        <thead class="thead-inverse">
            <tr> 
                <th>ID</th>
                <th>FECHA</th>
                <th>PROVEEDOR</th>
                <th class="tipopago">TIPO DE PAGO</th>
                <th class="iva">TOTAL IVA</th>
                <th class="subtotal">SUB TOTAL</th>
                <th>TOTAL</th>
                <th colspan="2" style="text-align: center">ACCIONES</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($compras as $item)
                <tr>
                    <td data-titulo="N° de venta: "scope="row">{{$item->id}}</td>
                    <td data-titulo="Fecha: ">{{$item->fecha}}</td>
                    <td data-titulo="Nombre: ">{{$item->nombre}}</td>
                    <td data-titulo="Modo de pago: " class="tipopago">{{$item->tipo}}</td>
                    <td data-titulo="Iva" class="iva">{{$item->total_iva}}</td>
                    <td data-titulo="Subtotal: " class="subtotal">{{$item->subtotal}}</td>
                    <td data-titulo="Total" >{{$item->total}}</td>
                    <td style="text-align: center">
                        <form method="POST" action="{{ route("compras.edit", $item->id) }}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-primary" type="submit">Editar</button>
                        </form>
                    </td>
                    <td style="text-align: center">
                        <form method="POST" action="{{ url("compras/{$item->id}/edit") }}">
                            @csrf
                            @method('DELETE')

                            <x-eliminar :ide="$item->id" />
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No hay Compras registradas</td>
                </tr>
                @endforelse
            </tbody>
    </table>
    {{$compras->links()}}
    </div>



@endsection