
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Factura de: {{$ventas[0]->nombre}}</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    </head>
    <body>
        
<div class="container" >
    <div style="text-align: center">
        <img src="{{ asset('img/Google-logo.jpg') }}" width="90%">
    </div>
    <h3>Codigo: {{$ventas[0]->id}}</h3>
    <div>Fecha: {{$ventas[0]->fecha}}</div>
    <div>Clinte: {{$ventas[0]->nombre}}</div>
    <div>Tipo de venta: {{$ventas[0]->tipo}}</div>
<br>

<table class="table">
    <tr>
        <th style="width: 100px">Producto</th>
        <th style="width: 80px">Cantidad</th>
        <th style="width: 80px">Precio</th>
        <th style="width: 80px">Iva</th>
        <th style="width: 80px">Subtotal</th>
    </tr>
@foreach ($detalles as $item)
    <tr>
        <td style="width: 120px">{{$item->nombre}}</td>
        <td style="width: 80px">{{$item->precio_venta}}</td>
        <td style="width: 80px">{{$item->cantidad}}</td>
        <td style="width: 80px">{{$item->iva}}</td>
        <td style="width: 80px">{{$item->subtotal}}</td>
    </tr>
@endforeach
    <tr>
        <td style="width: 120px"></td>
        <td style="width: 80px"></td>
        <td style="width: 80px"></td>
        <td  class="table-warning" style="width: 80px">Sub total:</td>
        <td  class="table-warning" style="width: 80px">{{$ventas[0]->subtotal}}</td>
    </tr>
    <tr>
        <td style="width: 120px"></td>
        <td style="width: 80px"></td>
        <td style="width: 80px"></td>
        <td  class="table-warning" style="width: 80px">Iva:</td>
        <td  class="table-warning" style="width: 80px">{{$ventas[0]->total_iva}}</td>
    </tr>
    <tr>
        <tH style="width: 120px"></tH>
        <tH style="width: 80px"></tH>
        <tH style="width: 80px"></tH>
        <tH class="table-secondary" style="width: 80px">Total:</tH>
        <tH class="table-secondary" style="width: 80px">{{$ventas[0]->total}}</tH>
    </tr>
</table>
 <br>  
</div>
</body>
</html>