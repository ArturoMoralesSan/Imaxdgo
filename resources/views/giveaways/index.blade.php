@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Giveaways')
@section('tab_title', 'Giveaways | ' . config('app.name'))
@section('description', 'Lista de giveaways.')
@section('css_classes', 'dashboard')

@section('content')

    <div class="dashboard-heading">

        <h1 class="dashboard-heading__title">
            Giveaways
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $giveaways->count() }} giveaways registrados.
        </p>

    </div>


    <div class="fluid-container mb-16">

        @include('components.alert')


        <section class="db-panel">

            <h3 class="db-panel__title">
                Lista de giveaways
            </h3>


            @if (! $giveaways->count())

                <p class="text-center py-1">
                    Por el momento no hay giveaways registrados.
                </p>

            @else

                <resource-table
                    :breakpoint="800"
                    :model="{{ $giveaways }}"
                    inline-template
                >

                    <table class="table size-caption mx-auto mb-16 md:table--responsive">

                        <thead>

                            <tr class="table-resource__headings">

                                <th>
                                    Nombre
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Inicio
                                </th>

                                <th>
                                    Finalización
                                </th>

                                <th>
                                    Preguntas
                                </th>

                                <th class="pr-4">
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="giveaway in resourceList"
                                class="table-resource__row"
                                :key="giveaway.id"
                            >

                                {{-- Nombre --}}
                                <td data-label="Nombre:">

                                    <a
                                        :href="$root.path + '/admin/giveaways/' + giveaway.id"
                                    >
                                        @{{ giveaway.name }}
                                    </a>

                                </td>


                                {{-- Estado --}}
                                <td data-label="Estado:">

                                    <span
                                        v-if="giveaway.active == 1"
                                        class="color-success"
                                    >
                                        Activo
                                    </span>

                                    <span
                                        v-else
                                        class="color-danger"
                                    >
                                        Inactivo
                                    </span>

                                </td>


                                {{-- Inicio --}}
                                <td data-label="Inicio:">

                                    @{{ giveaway.starts_at || 'Sin fecha' }}

                                </td>


                                {{-- Finalización --}}
                                <td data-label="Finalización:">

                                    @{{ giveaway.ends_at || 'Sin fecha' }}

                                </td>


                                {{-- Preguntas --}}
                                <td data-label="Preguntas:">

                                    @{{ giveaway.questions_count || 0 }}

                                </td>


                                {{-- Acciones --}}
                                <td
                                    class="table-resource__actions"
                                    data-label="Acciones:"
                                >

                                    {{-- Editar --}}
                                    <a
                                        class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2"
                                        :href="$root.path + '/admin/giveaways/' + giveaway.id + '/editar'"
                                    >
                                        <img
                                            class="svg-icon"
                                            src="{{ url('img/svg/edit.svg') }}"
                                        >
                                        Editar
                                    </a>

                                    <delete-button
                                        class="btn--danger table-resource__button"
                                        :url="$root.path + '/admin/giveaways/eliminar/' + giveaway.id"
                                        :resource-id="giveaway.id"
                                        :options="{ onDelete: onResourceDelete }"
                                    >
                                        <img
                                            style="width: 15px;"
                                            class="svg-icon"
                                            src="{{ url('img/svg/trash.svg') }}"
                                        >
                                        Eliminar
                                    </delete-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </resource-table>
            @endif
        </section>
    </div>

@endsection