@extends('layouts.app')
@section('title', __('Dashboard'))


@section('content')

<div class="container">
    
    @if (session()->has('message'))
        <div class="alert {{ Session::get('alert-class')}} alert-dismissible fade show" role="alert" style="width: 90%">
            {{ session('message') }} 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <x-tablas.buscar :tablas="$tablas"  :nombre="$nombre" :database="$database" />

    <div class="btn-group"  style="width:90%" >
        <form method="POST" action="{{ route("tablas.create") }}" class="form-control-sm">
            @csrf
            <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
            <input type="hidden" name="nombre_bd" value="{{$database}}">
            <button class="btn btn-primary" type="submit">Insertar en tabla {{$nombre}}</button>
        </form>
    
    
        <form method="POST"  action="{{ route("consulta") }}"  class="form-control-sm" >
            @csrf
            <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
            <input type="hidden" name="nombre_bd" value="{{$database}}">
             <x-tablas.consulta :nombre="$nombre" :campos="$campos" :database="$database"   /> 
        </form>
    
    </div>
    <br>
    <br>
    
    <div class="container">
        <table class="table table-striped  table-responsive" style="width:90%" id="myTable" >
            <thead>
                <tr> 
                    @for ($i = 0; $i < count($header); $i++)
                            <th>{{$header[$i]}}</th>
                    @endfor 
                </tr>
            </thead>
            <tbody>
               @forelse ($body as $item)
                        <tr>
                            @for ($i = 0; $i < count($header); $i++)
                                <?php $key =$header[$i];?>
                                    <td data-titulo="{{$key}}">{{$item->$key}}</td>
                            @endfor         
                        </tr>
                @empty
                <tr> 
                    <th  colspan="{{count($header)}}" style="text-align: center">No hay registros disponibles</th>
                </tr> 
                @endforelse
            </tbody>
        </table>
    </div> 
    
    
  
</div>

@endsection
