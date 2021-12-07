@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="container-fluid">
<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header"><h5></span> @yield('title')</h5></div>
			<div class="card-body">
				<h5>Hi <strong>{{ Auth::user()->name }},</strong> {{ __('You are logged in to ') }}{{ config('app.name', 'Laravel') }}</h5>
				<br> 
				<hr>		
			<div class="row w-100">
				@forelse ($productos as $item)
					<div class="col-md-2" style="margin: 5px; padding: 5px">
						<div class="card border-info mx-sm-1 p-3">
							<div class="card border-info text-info p-2" ><img src="{{$item->foto}}" alt=""> </span></div>
							<div class="text-info text-center mt-3"><h4>{{$item->nombre}}</h4></div>
							<div class="text-info text-center mt-2"><h1>$ {{$item->precio_venta}}</h1></div>
						</div>
					</div>
				@empty
					<div class="col-md-3">No hay productos registrados</div>
				@endforelse	
				 </div>				
			</div>
		</div>
	</div>
</div>
</div>
@endsection