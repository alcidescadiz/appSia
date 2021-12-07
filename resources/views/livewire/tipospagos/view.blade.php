@section('title', __('Tipospagos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h4>Tipospago Listing </h4>
						</div>
						@if (session()->has('message'))
						<div wire:poll.4s class="btn btn-sm btn-success" style="margin-top:0px; margin-bottom:0px;"> {{ session('message') }} </div>
						@endif
						<div>
							<input wire:model='keyWord' type="text" class="form-control" name="search" id="search" placeholder="Search Tipospagos">
						</div>
						<div class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal">
						<i class="fa fa-plus"></i>  Add Tipospagos
						</div>
					</div>
				</div>
				
				<div class="card-body">
						@include('livewire.tipospagos.create')
						@include('livewire.tipospagos.update')
				<div class="table-responsive" >
					<table class="table table-bordered table-sm"  style="width: 500px">
						<thead class="thead">
							<tr> 
								<td>#</td> 
								<th>Tipo</th>
								<td>ACTIONS</td>
							</tr>
						</thead>
						<tbody>
							@foreach($tipospagos as $row)
							<tr>
								<td>{{ $loop->iteration }}</td> 
								<td data-titulo="Tipo de pago:">{{ $row->tipo }}</td>
								<td width="120">
								<div class="btn-group">
									<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Actions
									</button>
									<div class="dropdown-menu dropdown-menu-right">
									<a data-toggle="modal" data-target="#updateModal" class="dropdown-item" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i> Edit </a>							 
									<a class="dropdown-item" onclick="confirm('Confirm Delete Tipospago id {{$row->id}}? \nDeleted Tipospagos cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i> Delete </a>   
									</div>
								</div>
								</td>
							@endforeach
						</tbody>
					</table>						
					{{ $tipospagos->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>