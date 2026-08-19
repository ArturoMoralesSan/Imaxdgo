@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Crear sorteo')
@section('tab_title', 'Crear sorteo | ' . config('app.name'))
@section('description', 'Crear un nuevo sorteo.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">

    <div class="dashboard-heading">

        <h1 class="dashboard-heading__title">
            Crear sorteo
        </h1>

    </div>


    <div class="fluid-container mb-16">

        <p class="mb-12">

            @include('components.alert')

            <span class="color-link">
                «
            </span>

            <a href="{{ route('giveaways.index') }}">
                Ver todos los sorteos
            </a>

        </p>


        <giveaway-form
            action="{{ route('giveaways.store') }}"
        >

        </giveaway-form>

    </div>

</section>

@endsection