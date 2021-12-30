
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
        <x-tablas.consulta :nombre="$nombre" :database="$database"  :campos="$campos" /> 
    </form>

    <form method="POST" action="{{ route("excel") }}" class="form-control-sm">
        @csrf
        <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
        <input type="hidden" name="nombre_bd" value="{{$database}}">
        <button class="btn btn-success" type="submit">Descargar tabla {{$nombre}} en Excel</button>
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
                @if ( $header[$i]->COLUMN_NAME != 'created_at' && $header[$i]->COLUMN_NAME != 'updated_at' && $header[$i]->COLUMN_NAME != 'deleted_at' )
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
                            @if ( $key != 'created_at' && $key != 'updated_at' && $key != 'deleted_at')
                                <td data-titulo="{{$key}}">{{$item->$key}}</td>
                            @endif
                        @endfor
                            <td>
                                <form method="POST" action="{{ route("tablas.edit") }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id"  value="{{$item->$noid}}">
                                    <input type="hidden" name="key_id"  value="{{$header[0]->COLUMN_NAME}}">
                                    <input type="hidden" name="nombre_tabla" value="{{$nombre}}">
                                    <input type="hidden" name="nombre_bd" value="{{$database}}">
                                    <button class="btn btn-primary" type="submit">Editar</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST"  action="{{ route("tabla.delete" , $item->$noid)}}"  >
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{$item->$noid}}"> 
                                    <input type="hidden" name="key_id"  value="{{$header[0]->COLUMN_NAME}}">
                                    <input type="hidden" name="nombre_tabla" value="{{$nombre}}"> 
                                    <input type="hidden" name="nombre_bd" value="{{$database}}">
                                     <x-eliminar :ide="$item->$noid"  /> 
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

