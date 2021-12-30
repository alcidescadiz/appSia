@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container">

    
    <x-tablas.buscar :tablas="$tablas"  :nombre="$nombre"  :database="$database"/>
    
<div>
    <h2>Editar elemento {{$id}} de la tabla: {{$nombre}}</h2>

    @if (session()->has('message_create'))
        <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
            {{ session('message_create') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{route('tabla.update', $id )}}" method="post" class="form-control-sm" style="width: 90%">
        @csrf
        @method('PUT')
        <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
        <input type="hidden" name="nombre_bd" value="{{$database}}">
        <input type="hidden" name="key_id" value="{{$header[0]->COLUMN_NAME}}">
        <input type="hidden" name="value_id" value="{{$id}}">
        @forelse ($body as $item)
            @for ($i = 0; $i < count($header); $i++)
                <?php $key =$header[$i]->COLUMN_NAME; ?>
                @if ( $key != 'created_at' && $key != 'updated_at'    && $key != 'deleted_at')
                    <div class="form-group">
                        <label for="">{{$key}}</label>
                        <input  
                        @if ($header[$i]->DATA_TYPE === 'double' || $header[$i]->DATA_TYPE === 'int' ||  $header[$i]->DATA_TYPE === 'bigint')
                                    type="number"  step="0.01" 
                        @elseif ($header[$i]->DATA_TYPE === 'date')
                                        type="date"  
                        
                        @else   type="text"
                        @endif  @if ($i === 0) readonly @endif  name="{{$key}}" required  class="form-control" value="{{$item->$key}}" >
                    </div>
                @endif
            @endfor
        @empty   
        @endforelse
        <div class="form-group"><button type="submit" class="btn btn-primary">Guardar cambios</button></div>
    </form>
</div>          
</div>
@endsection