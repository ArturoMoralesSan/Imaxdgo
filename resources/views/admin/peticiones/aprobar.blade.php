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
                <h1 class="h2">Solicitud aprobada</h1>
                <p >Se ha aprobado la siguiente petición</p>
                <ul>
                    <li><strong>ID:</strong> {{ $delete->record_id }}</li>
                    <li><strong>Nombre:</strong> {{ $delete->name }}</li>
                    <li><strong>Tipo:</strong> {{ $delete->type }}</li>
                    <li><strong>Sucursal:</strong> {{ $delete->branch }}</li>
                    <li><strong>Razón:</strong> {{ $delete->reason }}</li>
                    <li><strong>Monto:</strong> ${{ $delete->value }}</li>
                    <li><strong>Solicitado por:</strong> {{ $delete->deleted_by }}</li>       
                </ul>
            </div>    
        </div>
    </div>
</section>
@endsection
