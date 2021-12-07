@section('title', __('Clientes'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4>Clientes</h4>
						</div>
					
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Clientes">
						</div>
						<div class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal">
						<i class="fa fa-plus"></i>  Add Clientes
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@include('livewire.clientes.create')
						@include('livewire.clientes.update')
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead class="thead">
							<tr> 
								<td>#</td> 
								<th>Cedula</th>
								<th>Nombre</th>
								<th>Email</th>
								<th>Direccion</th>
								<th>Telefono</th>
								<td>ACTIONS</td>
							</tr>
						</thead>
						<tbody>
							@foreach($clientes as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td data-titulo="Cedula:">{{ $row->cedula }}</td>
								<td data-titulo="Nombre:">{{ $row->nombre }}</td>
								<td data-titulo="Email:">{{ $row->email }}</td>
								<td data-titulo="Dirección:">{{ $row->direccion }}</td>
								<td data-titulo="Teléfono:">{{ $row->telefono }}</td>
								<td width="90">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Actions
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-toggle="modal" data-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Edit </a>							 
									<a class="dropdown-item" onclick="confirm('Confirm Delete Cliente id {{$row->id}}? \nDeleted Clientes cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Delete </a>   
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>						
					{{ $clientes->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>