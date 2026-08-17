@extends('layout.master')

@section('title', 'Participar | ' . $giveaway->name)
@section('description', $giveaway->description)
@section('canonical', route('giveaways.participant', $giveaway->id))
@section('class', 'home')

@section('content')

<section class="giveaway-participant">

    <div class="container">

        <participant
            action="{{ route('giveaways.participate', $giveaway->id) }}"
            :giveaway="{{ $giveaway->toJson() }}"
        >
        </participant>

    </div>

</section>

@endsection