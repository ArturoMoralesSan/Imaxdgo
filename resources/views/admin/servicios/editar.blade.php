@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar servicios')
@section('tab_title', 'Editar servicios | ' . config('app.name'))
@section('description', 'Editar servicios.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Editar servicios
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
            :assigned-studies="{{ $assigned_studies }}"
            :service-data="{{ $service }}"
            :payment="3"
            :min-payment="1"
            :payments-data="{{ $payments }}"
            :assigned-payments="{{ $assigned_payments }}"            
        >
        </service-registration-form>
            
            
    </div>
</section>

@endsection
