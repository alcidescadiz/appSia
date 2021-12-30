@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">
    
    
    <div>
        <h2>Producto nuevo</h2>

        @if (session()->has('message_create'))
            <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
                {{ session('message_create') }} 
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

        <form action="{{route('productos.store')}}" method="POST" class="form-control-sm" style="width: 90%">
            @csrf
                <div class="form-group">
                    <label for="">Código</label>
                    <input type="text"   name="codigo"  class="form-control" >
                </div>
                <div class="form-group">
                    <label for="">Nombre del Producto</label>
                    <input type="text"   name="nombre"  class="form-control" >
                </div>
                <div class="form-group">
                    <label for="">Costo</label>
                    <input type="number" step="0.01"  name="costo" id="costo"   class="form-control" onchange="precio_producto()">
                </div>
                <div class="form-group">
                    <label for="">Asignar porcentaje de ganancia</label>
                    <input type="number" step="0.01"  name="porcentage_ganancia" id="porcentage_ganancia"  class="form-control" onchange="precio_producto()">
                </div>
                <div class="form-group">
                    <label for="">Asignar precio de venta</label>
                    <input type="number" step="0.01"  name="precio_venta" id="precio_venta"   class="form-control" readonly  onclick="precio_producto()">
                </div>
                <div>
                    <select name="gravable" id="" class="form-control">
                        <option ></option>
                        <option value="si">SI</option>
                        <option value="no">NO</option>

                    </select>
                </div>
                <br>
                <div>
                    <button type="submit" class="btn btn-primary">Ingresar datos</button>
                </div>
        </form>
    </div>          
</div>
<script>
    function precio_producto() {
        costo = document.getElementById("costo").value;
        porcentage_ganancia = document.getElementById("porcentage_ganancia").value;
        precio_venta = (costo*1) +(costo * porcentage_ganancia/100);
        document.getElementById("precio_venta").value = precio_venta.toFixed(2);
    }   
</script>
@endsection