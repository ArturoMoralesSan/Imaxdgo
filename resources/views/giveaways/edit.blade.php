@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar sorteo')
@section('tab_title', 'Editar sorteo | ' . config('app.name'))
@section('description', 'Editar sorteo.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">

    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Editar sorteo
        </h1>
    </div>

    <div class="fluid-container mb-16">

        <p class="mb-12">

            @include('components.alert')

            <span class="color-link">«</span>

            <a href="{{ route('giveaways.index') }}">
                Ver todos los sorteos
            </a>

        </p>

        <giveaway-form
            action="{{ route('giveaways.update', $giveaway->id) }}"
            method="put"
            :giveaway="{{ $giveaway->toJson() }}"
        >

        </giveaway-form>

    </div>

</section>

@endsection