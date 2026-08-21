@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Participantes')
@section('tab_title', 'Participantes | ' . config('app.name'))
@section('description', 'Lista de participantes del sorteo.')
@section('css_classes', 'dashboard')

@section('content')

    <div class="dashboard-heading">

        <h1 class="dashboard-heading__title">
            Participantes
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $participants->count() }} participantes registrados.
        </p>

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


        <section class="db-panel">

            <h3 class="db-panel__title">
                Lista de participantes
            </h3>


            @if (! $participants->count())

                <p class="text-center py-1">
                    Por el momento no hay participantes registrados.
                </p>

            @else

                <resource-table
                    :breakpoint="800"
                    :model="{{ $participants }}"
                    inline-template
                >

                    <table class="table size-caption mx-auto mb-16 md:table--responsive">

                        <thead>

                            <tr class="table-resource__headings">

                                <th>
                                    Folio
                                </th>

                                <th>
                                    Instagram
                                </th>

                                <th class="text-center">
                                    Respuestas correctas
                                </th>

                                <th class="text-center">
                                    Resultado
                                </th>
                                <th class="text-center">
                                    Tipo de premio
                                </th>


                                <th class="text-center">
                                    Premio
                                </th>

                                <th>
                                    Fecha
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="participant in resourceList"
                                class="table-resource__row"
                                :key="participant.id"
                            >

                                {{-- Folio --}}
                                <td data-label="Folio:">

                                    <strong>
                                        @{{ participant.folio }}
                                    </strong>

                                </td>


                                {{-- Instagram --}}
                                <td data-label="Instagram:">

                                    @{{ participant.instagram }}

                                </td>


                                {{-- Respuestas correctas --}}
                                <td
                                    class="text-center"
                                    data-label="Respuestas correctas:"
                                >

                                    <strong>
                                        @{{ participant.correct_answers }}
                                    </strong>

                                    /
                                    @{{ participant.questions_total }}

                                </td>


                                {{-- Resultado --}}
                                <td
                                    class="text-center"
                                    data-label="Resultado:"
                                >

                                    <span
                                        v-if="participant.correct_answers == participant.questions_total"
                                        class="color-success"
                                    >
                                        Correcto
                                    </span>

                                    <span
                                        v-else
                                        class="color-danger"
                                    >
                                        Incorrecto
                                    </span>

                                </td>

                                <td class="text-center" data-label="Tipo de premio:">

                                    @{{ participant.prize_type }}

                                </td>


                                {{-- Premio --}}
                                <td
                                    class="text-center"
                                    data-label="Premio:"
                                >

                                    <span
                                        v-if="participant.prize_delivered"
                                        class="color-success"
                                    >
                                        Entregado
                                    </span>

                                    <span
                                        v-else
                                        class="color-danger"
                                    >
                                        No entregado
                                    </span>

                                </td>


                                {{-- Fecha --}}
                                <td data-label="Fecha:">

                                    @{{ participant.created_at_formatted || 'Sin fecha' }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </resource-table>

            @endif

        </section>

    </div>

@endsection