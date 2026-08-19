@extends('layout.master')
{{-- Metadata --}}
@section('title', config('app.name'))
@section('description', 'Iniciar-sesion.')
@section('canonical', config('app.url'))
@section('class', 'home')
@section('content')
<section class="section section-login">
    
    <div class="container">
        <div style="height:70vh;" class="login-form d-flex flex-col items-center justify-center">
            
            <div class="form-boxed d-flex flex-col items-center justify-center" style="max-width:400px;">
                <img class="center login-image" src="{{ url('img/imax-logo1.png') }}" alt="">
                <h1 class="h2">Solicitud rechazada</h1>
                <p style="text-align:center;" >No se ha encontrado registro</p>
            </div>    
        </div>
    </div>
</section>
@endsection
