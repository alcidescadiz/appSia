<div class="container" style="background-color: #EADEDE; border-radius: 20px; padding: 20px">
    <div class="row no-gutters">    
    <div class="col-6 col-md-4" >
        <h2><i>Indicador de ventas por tipo de pago</i></h2>
        <h5><li>Ganancias Acumuladas: {{$ganancia[0]->ganancia}}</li></h5>
        <div class="row justify-content-center">
        <table class="table table-striped table-inverse table-responsive" >
            <thead class="thead-inverse">
                <tr> 
                    <th>ID</th>
                    <th>TIPO DE PAGO</th>
                    <th>ESTATUS</th>
                    <th>TOTAL</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($indicadorV as $item)
                    <tr>
                        <td scope="row">{{$item->id}}</td>
                        <td>{{$item->tipo}}</td>
                        <td>{{$item->estatus}}</td>
                        <td>{{$item->total}}</td>
                    </tr>
                    @endforeach
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
