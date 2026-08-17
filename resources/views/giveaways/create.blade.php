@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Crear giveaway')
@section('tab_title', 'Crear giveaway | ' . config('app.name'))
@section('description', 'Crear un nuevo giveaway.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">

    <div class="dashboard-heading">

        <h1 class="dashboard-heading__title">
            Crear giveaway
        </h1>

    </div>


    <div class="fluid-container mb-16">

        <p class="mb-12">

            @include('components.alert')

            <span class="color-link">
                «
            </span>

            <a href="{{ route('giveaways.index') }}">
                Ver todos los giveaways
            </a>

        </p>


        <giveaway-form
            action="{{ route('giveaways.store') }}"
        >

        </giveaway-form>

    </div>

</section>

@endsection