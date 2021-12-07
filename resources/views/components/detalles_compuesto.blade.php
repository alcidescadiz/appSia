<div>
    <h3>Detalles del Compuesto:</h3>

    <table class="table table-striped table-inverse table-responsive" style="margin:5px; padding: 5px; width: 95% ">
        <thead class="thead-inverse">
            <tr>
                <th>Producto</th>
                <th>Costo</th>
                <th>Cantidad</th>
                <th>Iva</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody class="thead-inverse">
            @forelse ($detalles as $item)
                <tr>
                    <td data-titulo="Producto:"> {{$item->nombre}} </td>
                    <td data-titulo="Costo:"> {{$item->costo}}  </td>
                    <td data-titulo="Cantidad:">{{$item->cantidad}}  </td>
                    <td data-titulo="Iva:"> {{$item->iva}} </td>
                    <td data-titulo="Subtotal:">{{$item->subtotal}} </td>
                    <td>
                        <form method="POST" action="{{ url("detallescompuestos/{$item->id}") }}">
                            @csrf
                            @method('DELETE')
                            <x-eliminar :ide="$item->id" />
                        </form>
                    </td> 
                </tr> 
            @empty
                <tr>
                    <th colspan="6">No hay detalles en el compuesto</th>
               </tr>         
        
            @endforelse
        </tbody>
    </table>
</div>