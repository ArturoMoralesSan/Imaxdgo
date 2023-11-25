@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Agregar servicios')
@section('tab_title', 'Agregar servicios | ' . config('app.name'))
@section('description', 'Asignar servicios.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Asignar servicios
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/servicios/') }}">Ver todos los servicios</a>
        </p>
        
        <service-registration-form 
            action="{{ url('admin/servicios/crear') }}"
            :study="4"
            :min-study="1"
            :branches="{{ $branches }}"
            :studies-data="{{ $studies }}"
            :assigned-studies="[]"
            :service-data="[]"
            :payment="3"
            :min-payment="1"
            :payments-data="{{ $payments }}"
            :assigned-payments="[]"
        >
        </service-registration-form>

            
            
    </div>
</section>

@endsection
