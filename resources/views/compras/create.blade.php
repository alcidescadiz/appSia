@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container"><h2>Factura</h2></div>
    
<div class="container" style="background-color: #EEEBDD; border-radius: 20px; padding: 20px">
    <div class="row no-gutters">

    <div class="col-sm-6 col-md-4" >
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width:90%; text-aling:center; margin 5px;">
            <ul style="text-aling:center;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    <form action="{{URL::to('/compras')}}" method="POST" style="margin:5px; padding: 5px; width: 95% ">
        {!! csrf_field() !!} 
        <div class="form-group">
            <label for="">Codigo</label>
            <input type="number"  class="form-control" name="id" id="" readonly value="{{$idnew}}">
        </div>
        <div class="form-group">
        <label for="">Fecha</label>
        <input type="date" class="form-control" name="fecha" id="">
        </div>
        <div class="form-group">
            <label for="">Proveedor</label>
            <select  class="form-control" name="id_proveedor" id="">
                <option value="">--Seleccione un proveedor--</option>
                @foreach ($Lproveedores as $item)
                <option value="{{$item->id}}">{{$item->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Tipo de Pago</label>
            <select  class="form-control" name="id_tipo_pago" id="">
                <option value="">--Seleccione tipo de pago--</option>
                @foreach ($Ltipospagos as $item)
                <option value="{{$item->id}}">{{$item->tipo}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Total iva</label>
            <input type="number" step="0.01" class="form-control" name="total_iva" value="{{$tiva}}" readonly>
        </div>
        <div class="form-group">
            <label for="">Subtotal</label>
            <input type="number" step="0.01" class="form-control" name="subtotal" value="{{$tsubtotal}}" readonly>
        </div>
        <div class="form-group">
            <label for="">Total</label>
            <input type="number" step="0.01" class="form-control" name="total" value="{{$ttotal}}" readonly>
        </div>
        <input name="" id="" class="btn btn-primary" type="submit" value="Guardar">

    </form>
    </div>

    <div class="col-sm-5 col-md-8">
        <h3>Seleccione un producto:</h3>
        <form action="{{URL::to('/detalles')}}" method="post" style="margin:5px; padding: 5px; width: 95% ">
            {!! csrf_field() !!} 
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr>
                    <th>Producto</th>
                    <th>Costo</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
                <tbody>
                    <tr>
                        <td data-titulo="Producto:">
                                <select class="custom-select" name="" id="selectproducto" style="width: 150px" onchange="detalle_costo()">
                                    <option selected>Select one</option>
                                    @foreach ($productos as $item)
                                    <option value="{{$item->costo}}">{{$item->nombre}}</option>
                                    @endforeach
                                   
                                </select>
                                <input type="hidden" name="id_productos" id="productocopia">
                            </div>
                        </td>
                        <td data-titulo="Costo:">
                            <input type="number" name="costo" id="costo" step="0.01" style="width: 100px" class="form-control" onchange="detalle_subtotal()">
                        </td>
                        <td data-titulo="Cantidad:">
                            <input type="number" name="cantidad" id="cantidad" step="0.01" style="width: 100px" class="form-control" onchange="detalle_subtotal()">
                        </td>
                        <td data-titulo="Subtotal:">
                            <input readonly type="number" name="subtotal" step="0.01" id="subtotal" style="width: 100px" class="form-control">
                        </td>
                        <td>
                        <input type="hidden" name="id_compras" value="{{$idnew}}">
                        <input name="" id="" class="btn btn-success" type="submit" value="Agregar">
                        </td>
                    </tr>
                </tbody>
        </table>
    </form>
    <script>
        function detalle_subtotal() {
            costo = document.getElementById("costo").value;
            cantidad = document.getElementById("cantidad").value;
            calculo = (costo * cantidad);
            document.getElementById("subtotal").value = calculo.toFixed(2);
        }
        function detalle_costo(){
            costo=document.getElementById("selectproducto").value;
            document.getElementById("costo").value = costo;
            //el valor de texto del select producto
            combo = document.getElementById("selectproducto");
            selected = combo.options[combo.selectedIndex].text;
            document.getElementById("productocopia").value = selected;
            
        }
        
    </script>
    <hr>
        <x-detalles_compra :detalles='$detalles' :idnew='$idnew'/>
        
    </div>
    </div></div>
@endsection