@section('title', __('Productos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
				<div class="card" style="align-items: center">
					<div class="card-header">
						<div class="btn btn-sm btn-info">
							<a style="color: white; text-decoration: none" href="{{ url('/compuestos') }}"><i class="fa fa-plus"></i> Compuestos</a> 
						</div>
						<div class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal">
							<i class="fa fa-plus"></i>  Productos
						</div>
					</div>
				</div>
				<br>
				<div class="card-header">
					<div style="display: flex; align-items: center;">
						<div class="float-left">
							<h4>Lista de Productos </h4>
						</div>
						<div class="float-left" style="min-width: 100px; margin:5px">
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Busqueda de Productos">
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
				</div>
				
				
				<div class="card-body">
						@include('livewire.productos.create')
						@include('livewire.productos.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr> 
								<td>#</td> 
								<th>Codigo</th>
								<th>Nombre</th>
								<th>Costo</th>
								<th>Porcentage Ganancia</th>
								<th>Precio Venta</th>
								<th>Gravable</th>
								<th>Estatus</th>
								<th style="text-align: center" colspan="2">Acciones</th>
							</tr>
						</thead>
						<tbody>
							@foreach($productos as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td data-titulo="Código:">{{ $row->codigo }}</td>
								<td data-titulo="Nombre:">{{ $row->nombre }}</td>
								<td data-titulo="Costo:">{{ $row->costo }}</td>
								<td data-titulo="% de Ganancia:">{{ $row->porcentage_ganancia }}</td>
								<td data-titulo="Precio de venta:">{{ $row->precio_venta }}</td>
								<td data-titulo="Gravable:">{{ $row->gravable }}</td>
								<td data-titulo="Estatus:">{{ $row->estatus }}</td>
								<td style="text-align: center">
									@if ($row->tipo ==='compuesto')
										<form method="POST" action="editcompuesto/{{$row->id}}">
											@csrf
											@method('PUT')
											<button class="btn btn-primary" type="submit"></i>Editar Compuesto</button>
										</form>
									@endif

									@if ($row->tipo ==='unitario')
										<a data-toggle="modal" data-target="#updateModal" class="btn btn-primary"  wire:click="edit({{$row->id}})"></i> Editar </a>
									@endif
								</td>
								<td style="text-align: center">		 
									<a class="btn btn-warning" onclick="confirm('Confirm Delete Producto id {{$row->id}}? \nDeleted Productos cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"></i> Eliminar </a>   
							
								</td>
							@endforeach
						</tbody>
					</table>						
					{{ $productos->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>