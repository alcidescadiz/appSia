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

    <div class="btn-group"  style="width:90%" >
        <form method="POST" action="{{ route("productos.create") }}" class="form-control-sm">
            @csrf
            <button class="btn btn-primary" type="submit">Nuevo producto</button>
        </form>

        <form method="GET" action="{{ url('/compuestos') }}" class="form-control-sm">
            @csrf
            <button class="btn btn-primary" type="submit">Producto Compuesto</button>
        </form>

        <form method="POST" action="{{ route("excel") }}" class="form-control-sm">
            @csrf
            <input type="hidden" name="nombre_tabla" value="productos">
            <input type="hidden" name="nombre_bd" value="appsialaravel">
            <button class="btn btn-success" type="submit">Descargar tabla Productos en Excel</button>
        </form>
    
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="container">
        <table id="example" id="example" class="table table-striped table-bordered display"  style="width:100%;" >
            <thead>
                <tr> 
                @for ($i = 0; $i < count($header); $i++)
                    @if ( $header[$i]->COLUMN_NAME != 'created_at' && $header[$i]->COLUMN_NAME != 'updated_at' && $header[$i]->COLUMN_NAME != 'deleted_at' && $header[$i]->COLUMN_NAME != 'foto' )
                        <th>{{$header[$i]->COLUMN_NAME}}</th>
                    @endif
                @endfor 
                    <th style="width: 80px">Editar</th>
                    <th style="width: 80px">Eliminar</th>
                </tr>
            </thead>
            <tbody>
               @forelse ($body as $item)
                        <tr>
                            @for ($i = 0; $i < count($header); $i++)
                                <?php $key =$header[$i]->COLUMN_NAME; $noid =$header[0]->COLUMN_NAME;?>
                                @if ( $key != 'created_at' && $key != 'updated_at' && $key != 'deleted_at'  && $key != 'foto')
                                    <td data-titulo="{{$key}}">{{$item->$key}}</td>
                                @endif
                            @endfor
                                <td>
                                    @if ($item->tipo ==='compuesto')
										<form method="POST" action="{{ route("compuesto.edit", $item->id) }}">
											@csrf
											@method('PUT')
											<button class="btn btn-primary" type="submit">Editar</button>
										</form>
									@endif

									@if ($item->tipo ==='unitario')
                                    <form method="POST" action="{{ route("producto.edit", $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id"  value="{{$item->id}}">
                                        <input type="hidden" name="tipo"  value="{{$item->tipo}}">
                                        <button class="btn btn-primary" type="submit">Editar</button>
                                    </form>
									@endif

                                </td>
                                <td>
                                    <form method="POST"  action="{{ route("productos.delete" , $item->$noid)}}"  >
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{$item->id}}"> 
                                         <x-eliminar :ide="$item->id"  /> 
                                    </form>
                                </td>
                        </tr>
                @empty
                <tr> 
                    <th  colspan="{{count($header)}}" style="text-align: center">No hay registros disponibles</th>
                </tr> 
                @endforelse
            </tbody>
        </table>
    </div> 
    <script>
    
        $(document).ready(function() {
            $('#example').DataTable( {
                "language": {
                "lengthMenu": "Muestra _MENU_ registros por página",
                "zeroRecords": "Nada coincide en la busqueda",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros para mostrar",
                "infoFiltered": "(filtrado de _MAX_ registros)"
                },
            } );
        } );
    </script>
</div>

@endsection
