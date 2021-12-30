@extends('layouts.app')
@section('title', __('Welcome'))
@section('content')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<div class="container-fluid">
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5> @yield('title')</h5></div>
            <div class="card-body">
              <h5>  
            @guest
				
				{{ __('Welcome to') }} {{ config('app.name', 'Laravel') }} !!! </br>
				Please contact admin to get your Login Credentials or click "Login" to go to your Dashboard.
                
			@else
					Hi {{ Auth::user()->name }}, Welcome back to {{ config('app.name', 'Laravel') }}.
            @endif	

            
        </h5>
        </div>
        
            <textarea   name="ckeditor" id="editor"  cols="30" rows="10" ></textarea>
        </div>
    </div>
    <script>
        CKEDITOR.replace( 'editor', {
            language: 'es',
            uiColor: '#a9a9a9'
        });

    </script>
</div>

  
</div>
@endsection