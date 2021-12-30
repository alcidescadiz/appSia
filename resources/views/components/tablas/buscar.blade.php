
<h3>Seleccionar una tabla:</h3>
<div style="width:90%" >
    <form action="{{route('tabla')}}" method="post"   > 
        @csrf
        <div  class="btn-group">
            <div style="padding: 5" >
                <select class="form-control" name="nombre_tabla" required  style="width:300px; ">
                @if ( isset($nombre)  )
                    <option value="{{$nombre}}">{{$nombre}}</option>
                @else
                <option value="">Seleccione una tabla de la base de datos</option>
                @endif
                    @forelse ($tablas as $item)
                        <option value="{{$item}}">{{$item}}</option>  
                    @empty
                    @endforelse
                </select>
            </div>
            <input type="hidden" name="nombre_bd" value="appsialaravel"  >
            <div style="margin: 5" >
                <button type="submit" style="width:85px; " class="btn btn-outline-success" >BUSCAR</button>
            </div>
        </div>
    </form>
</div>
<br>
