@extends('layout.master')

@section('title', 'Validar participante | ' . config('app.name'))
@section('description', 'Validar participante del giveaway.')
@section('canonical', route('giveaways.validate'))
@section('class', 'home')

@section('content')

<section class="giveaway-participant">

    <div class="container">

        <participant-validator
            search-action="{{ url('admin/giveaways/validar') }}"
            validate-action="{{ route('giveaways.validate.store') }}"
        >
        </participant-validator>

    </div>

</section>

@endsection