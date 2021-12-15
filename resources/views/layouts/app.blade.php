<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>appSia</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        
        <!-- Scripts -->
     
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
        jQuery(document).ready(function($){
            $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            });
        })
        </script>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <style>
            body {
                overflow-x: hidden;
            }
            #sidebar-wrapper {
                min-height: 100vh;
                margin-left: -15rem;
                -webkit-transition: margin .25s ease-out;
                -moz-transition: margin .25s ease-out;
                -o-transition: margin .25s ease-out;
                transition: margin .25s ease-out;
            }
            #sidebar-wrapper .sidebar-heading {
                padding: 0.875rem 1.25rem;
                font-size: 1.2rem;
            }
            #sidebar-wrapper .list-group {
                width: 15rem;
            }
            #page-content-wrapper {
                min-width: 100vw;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            @media (min-width: 770px) {
                #sidebar-wrapper {
                    margin-left: 0;
                }
                #page-content-wrapper {
                    min-width: 0;
                    width: 100%;
                }
                #wrapper.toggled #sidebar-wrapper {
                    margin-left: -15rem;
                }  
            }
            @media (max-width: 500px) {
                table{
                    width: 95%;
                    margin: 5px
                }
                table thead {
                    display: none
                }
                table tr{
                    display: flex;
                    flex-direction: column;
                    border: 1px solid grey;
                    padding: 1em;
                    margin-bottom: : 1em;
                }
                table td[data-titulo]{
                    display: flex;
                    color:black
                    
                }
                table td[data-titulo]::before{
                    content: attr(data-titulo);
                    width: 120px;
                   
                }
                table button{
                    display: flex;
                }
            }

        }
        </style> 
        @livewireStyles  
        @laravelPWA 
    </head>
    <body>
        <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading" style="text-align: center; color: white"><h3><img src="{{ asset('favicon.ico') }}" width="55px"> appSia</h3></div>
        <div class="list-group list-group-flush">
            @auth()
                <!--Nav Bar Hooks - Do not delete!!-->
		               
                    <a href="{{ url('/clientes') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond">Clientes</a> 

                    <a href="{{ url('/proveedores') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Proveedores</a> 

                    <a href="{{ url('/tipospagos') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Tipospagos</a> 

                    <a href="{{ url('/productos') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Productos</a> 

                    <a href="{{ url('/compras') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond">Compras</a> 

                    <a href="{{ url('/compras.create') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond">Factura Compra</a> 

                    <a href="{{ url('/ventas') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond">Ventas</a> 

                    <a href="{{ url('/facturav') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond">Factura Venta</a> 

                    <a href="{{ url('/almacen') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Almacen</a> 
 
                    <a href="{{ url('/indicadores') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Indicadores</a> 

                    <a href="{{ url('/pagar') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Cuentas por pagar</a> 

                    <a href="{{ url('/cobrar') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Cuentas por cobrar</a> 

                    <a href="{{ url('/ganancias') }}" class="list-group-item list-group-item-action bg-dark" style="color: blanchedalmond"> Ganancias</a> 
 
                    @endauth()
                </div>
            </div>
            <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
                    <button class="btn btn-secondary" id="menu-toggle">Menu</button>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="text-align: center">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            @auth()
                            @if ($n_cuentas_pagar[0]->total >0)
                            <li class="nav-item active">
                                    <a href="{{ url('/pagar') }}" class="btn btn-secondary"  style="margin: 5px"> Cuentas por pagar <span class="badge badge-success"> {{$n_cuentas_pagar[0]->total}}</span></a>
                            </li>
                            @endif
                            @if ($n_cuentas_cobrar[0]->total >0)
                            <li class="nav-item active">
                                    <a href="{{ url('/cobrar') }}" class="btn btn-primary" style="margin: 5px"> Cuentas por cobrar <span class="badge badge-success"> {{$n_cuentas_cobrar[0]->total}}</span></a>  
                            </li>
                            @endif
                            @endauth()
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                            </li>
                <li class="nav-item dropdown">
                                        <!-- Right Side Of Navbar -->
                                        <ul class="navbar-nav ml-auto">
                                            <!-- Authentication Links -->
                                            @guest
                                                @if (Route::has('login'))
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                                    </li>
                                                @endif
                                                
                                                @if (Route::has('register'))
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                                    </li>
                                                @endif
                                            @else
                                                    <li class="nav-item dropdown">
                                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                                        {{ Auth::user()->name }}
                                                    </a>
                    
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                                           onclick="event.preventDefault();
                                                                         document.getElementById('logout-form').submit();">
                                                            {{ __('Logout') }}
                                                        </a>
                    
                                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                </li>
                                            @endguest
                                        </ul>

                    </li>
                </ul>
                </div>
            </nav>

        <main class="py-2">
            @yield('content')
        </main>
        @livewireScripts
        <script type="text/javascript">
            window.livewire.on('closeModal', () => {
                $('#exampleModal').modal('hide');
            });
            window.livewire.on('closeModalupdate', () => {
                $('#updateModal').modal('hide');
            });
        </script>

    </body>
</html>