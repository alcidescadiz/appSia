@extends('layouts.app')
@section('title', __('Dashboard'))
@section('content')
<script src="{{ asset('js/chart.min.js') }}"></script>

<div style="text-align: center; margin:10px; padding: 10px">
<a href="{{ url('/indicadores/{compras}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Compras</a> 
<a href="{{ url('/indicadores/{ventas}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Ventas</a> 
<a href="{{ url('/indicadores/{entrefechas}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores entre fechas</a> 
<a href="{{ url('/hoy') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores de Hoy</a>
<a href="{{ url('/indicadores/{pordia}') }}" class="btn btn-info" style="margin:5px; padding: 5px"> Indicadores por día</a>
</div>
<br>


<div class="container" style="background-color: #EEEBDD; border-radius: 20px; padding: 20px">
    <div class="row no-gutters">    
    <div class="col-6 col-md-4" >
        <h3><i>Indicadores de Compras del día:  {{$fecha1}}</i></h3>

        <div class="row justify-content-center">
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr> 
                    <th>ID</th>
                    <th>TIPO DE PAGO</th>
                    <th>ESTATUS</th>
                    <th>TOTAL</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($indicadorC as $item)
                    <tr>
                        <td data-titulo="Id" scope="row">{{$item->id}}</td>
                        <td data-titulo="Tipo">{{$item->tipo}}</td>
                        <td data-titulo="Estatus">{{$item->estatus}}</td>
                        <td data-titulo="Monto total">{{$item->total}}</td>
                    </tr>
                    @empty
                        <tr><td colspan="4">No hay registros que analizar</td></tr>
                    @endforelse
                </tbody>
        </table>
        </div>
    </div>

<div class="col-sm-6 col-md-8">  
    <canvas id="myChart2"  style=" height:200px; width:400px"></canvas>
        <script>
        const ctx2 = document.getElementById('myChart2').getContext('2d');
        const myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($indicadorC as $item)
                        "{{$item->tipo}}",
                     @endforeach
                ],
                datasets: [{
                    label: '# Total Compras Acumuladas',
                    data: [
                     @foreach ($indicadorC as $item)
                        {{$item->total.','}}
                     @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {

                }
            }
        });
        </script>
    </div>
    </div>
</div>
<br>
<div class="container" style="background-color: #EEEBDD; border-radius: 20px; padding: 20px">
    <div class="row no-gutters">    
    <div class="col-6 col-md-4" >
        <h3><i>Indicadores de Ventas de  {{$fecha1}}</i></h3>
        <h5><li>Ganancias acumuladas: {{$ganancias[0]->ganancia}}</li></h5>
        <div class="row justify-content-center">
        <table class="table table-striped table-inverse table-responsive">
            <thead class="thead-inverse">
                <tr> 
                    <th>ID</th>
                    <th>TIPO DE PAGO</th>
                    <th>ESTATUS</th>
                    <th>TOTAL</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($indicadorV as $item)
                    <tr>
                        <td data-titulo="Id" scope="row">{{$item->id}}</td>
                        <td data-titulo="Tipo">{{$item->tipo}}</td>
                        <td data-titulo="Estatus">{{$item->estatus}}</td>
                        <td data-titulo="Total">{{$item->total}}</td>
                    </tr>
                    @empty
                        <tr><td colspan="4">No hay registros que analizar</td></tr>
                    @endforelse
                </tbody>
        </table>
        </div>
    </div>

<div class="col-sm-6 col-md-8">  
    <canvas id="myChart"  style=" height:200px; width:400px"></canvas>
        <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($indicadorV as $item)
                        "{{$item->tipo}}",
                     @endforeach
                ],
                datasets: [{
                    label: '# Total Ventas Acumuladas',
                    data: [
                     @foreach ($indicadorV as $item)
                        {{$item->total.','}}
                     @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {

                }
            }
        });
        </script>
    </div>
    </div>
</div>


<br>
<script src="{{ asset('js/jquery.min.js') }}"></script>
@endsection