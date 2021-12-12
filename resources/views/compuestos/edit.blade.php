@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container"><h2>Editar Producto compuesto</h2></div>
    
<div class="container" style="background-color: #F7DBF0; border-radius: 20px; padding: 20px">
    <div class="row no-gutters">

    <div class="col-sm-6 col-md-4" >
    @if (session()->has('message'))
        <div class="alert {{ Session::get('alert-class', 'alert alert-success alert-dismissible fade show') }}" role="alert" style="width:90%; text-aling:center; margin 5px;">
            {{ session('message') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
    @endif
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
    <form action="{{url('updatecompuesto/'.$idnew)}}" method="POST" style="margin:5px; padding: 5px; width: 95% ">
        @csrf
        {{ method_field('PUT')}} 
        <div class="form-group">
            <label for="">Id producto</label>
            <input type="text" class="form-control" name="id" readonly value="{{$idnew}}">
        </div>
        <div class="form-group">
            <label for="">Codigo</label>
            <input type="text" class="form-control" name="codigo" readonly value="{{$productocompuesto[0]->codigo}}">
        </div>
        <div class="form-group">
        <label for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" value="{{$productocompuesto[0]->nombre}}" readonly>
        </div>
        <div class="form-group">
            <label for="">Costo</label>
            <input type="number" step="0.01" class="form-control" name="costoc" readonly id="costoc"  value="{{$ttotal}}" onchange="precio_venta_compuesto() ">
        </div>
        <div class="form-group">
            <label for="">Porcentaje de Ganancia</label>
            <input type="number"  step="0.01" class="form-control" required name="porcentage_ganancia"  id="porcentage_ganancia" onchange="precio_venta_compuesto()"  value="{{$productocompuesto[0]->porcentage_ganancia}}" >
        </div>
        <div class="form-group">
            <label for="">Precio de Venta</label>
            <input type="number" step="0.01" class="form-control" name="precio_venta" id="precio_venta"  readonly onchange="precio_venta_compuesto() "  value="{{$precio_venta}}">
        </div>
        <div class="form-group">
            <label for="">Gravable</label>
            <select type="text" class="form-control" name="gravable">
                <option value="{{$productocompuesto[0]->gravable}}">{{$productocompuesto[0]->gravable}}</option>
                <option value="si">SI</option>
                <option value="no">NO</option>
            <select>
        </div>
        <div class="form-group">
            <label for="">Link de imagen del producto</label>
            <input type="text" class="form-control" name="foto" id="foto" required  value="{{$productocompuesto[0]->foto}}">
        </div>
        <input  class="btn btn-primary" type="submit" value="Guardar">

    </form>
    </div>

    <div class="col-sm-5 col-md-8">
        <h3>Seleccione un producto:</h3>
        <form action="{{URL::to('/detallescompuestosedit')}}" method="POST" style="margin:5px; padding: 5px; width: 95% ">
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
                    <input type="hidden" name="id_compuesto" value="{{$idnew}}">
                    <input name="" id="" class="btn btn-success" type="submit" value="Agregar">
                    </td>
                    </tr>
                </tbody>
        </table>
    </form>
    <script>
        function precio_venta_compuesto() {
            costo = document.getElementById("costoc").value;
            porcentaje = document.getElementById("porcentage_ganancia").value;
            calculo = costo*1 + (costo * porcentaje/100);
            document.getElementById("precio_venta").value = calculo.toFixed(2);
        }
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
    <x-detalles_compuesto_edit :detalles='$detalles' :idnew='$idnew'/>
        
    </div>
    </div></div>
@endsection