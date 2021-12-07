@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')

<div class="container" style="margin:5px; padding: 5px; width: 95% ">
    <div>
        <h2><i>Ganancias por Productos</i></h2>
        <h3>Total ganancias acumuladas= {{$ganancia[0]->ganancia}} $</h3>
    </div>
    <br>
    <div class="col-sm-12 col-md-12" style=" overflow: auto" >
    <table class="table table-striped" style="margin:5px; padding: 5px; width: 95% ;">
        
        <thead class="thead-inverse">
                <tr style="text-align: center"> 
                    <th>CODIGO</th>
                    <th>NOMBRE</th>
                    <th>COSTO</th>
                    <th>CANTIDAD COMPRADAS</th>
                    <th>TOTAL COMPRAS</th>
                    <th>PRECIO</th>
                    <th>CANTIDAD VENDIDAS</th>
                    <th>TOTAL VENTAS</th>
                    <th>GANANCIAS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ganancias as $item)
                <tr style="text-align: center;">
                    <td data-titulo="Código:">{{$item->codigo}}</td>
                    <td data-titulo="Producto:">{{$item->nombre}}</td>
                    <td data-titulo="Costo:">{{$item->costo}}</td>
                    <td data-titulo="Ctd Compras:">{{$item->cantidad_compras}}</td>
                    <td data-titulo="Monto compras:">{{$item->compras}}</td>
                    <td data-titulo="Precio de venta:">{{$item->precio_venta}}</td>
                    <td data-titulo="Ctd ventas:">{{$item->cantidad_ventas}}</td>
                    <td data-titulo="Monto ventas:">{{$item->ventas}}</td>
                    <td data-titulo="Ganancias:">{{$item->ganancias}}</td>
                </tr>
                @endforeach
            </tbody>
    </table>
    </div>

</div>

@endsection