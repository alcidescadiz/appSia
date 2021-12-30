@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">

    
<div>
    <h2>Editar elemento {{$id}} de la tabla Productos</h2>

    @if (session()->has('message_create'))
        <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
            {{ session('message_create') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{route('productos.update', $id )}}" method="post" class="form-control-sm" style="width: 90%">
        @csrf
        @method('PUT')
        @forelse ($body as $item)
            @for ($i = 0; $i < count($header); $i++)
                <?php $key =$header[$i]->COLUMN_NAME; ?>
                @if ( $key != 'created_at' && $key != 'updated_at'    && $key != 'deleted_at')
                    <div class="form-group">
                        <label for="">{{$key}}</label>
                        <input  
                        @if ($header[$i]->COLUMN_NAME === 'costo' || $header[$i]->COLUMN_NAME === 'porcentage_ganancia' ||  $header[$i]->COLUMN_NAME === 'precio_venta')
                                    type="number"  step="0.01" id="{{$key}}" onchange="precio_producto()"
                        @elseif ($header[$i]->DATA_TYPE === 'date')
                                        type="date"  
                        
                        @else   type="text"
                        @endif  @if ($i === 0) readonly @endif  name="{{$key}}"  required  class="form-control" value="{{$item->$key}}" >
                    </div>
                @endif
            @endfor
        @empty   
        @endforelse
        <div class="form-group"><button type="submit" class="btn btn-primary">Guardar cambios</button></div>
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