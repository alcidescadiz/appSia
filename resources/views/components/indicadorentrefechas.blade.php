<div class="container" style="background-color: #EADEDE; border-radius: 20px; padding: 20px">
    <h2><i>Indicador entre fechas</i></h2>
    <div class="row no-gutters" >    
        <form action="{{URL::to('/entrefechas')}}" method="post">
            @csrf

            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }} 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width:90%; text-aling:center; margin 5px;">
                <ul style="text-aling:center;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <div class="form-group">
            <label for="">Fecha inicio</label>
            <input type="date" name="fecha1" id="" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Fecha final</label>
                <input type="date" name="fecha2" id="" class="form-control">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        
    </div>
</div>
