@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'servicios')
@section('tab_title', 'servicios | ' . config('app.name'))
@section('description', 'Lista de servicios.')
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Servicios
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $services->count() }} servicios registrados.
        </p>
    </div>

    <div class="fluid-container mb-16">
        @include('components.alert')

        
        <form-search 
            selected="{{ app('request')->input('search') }}"
        >
        <template slot="svg-search">
            <img class="search-form_icon" src="{{ url('img/svg/search.svg') }}" alt="">
        </template>
        </form-search>
           
        <section class="db-panel">
            <h3 class="db-panel__title">
                Lista de servicios
            </h3>

            @if (! $services->count())
                <p class="text-center py-1">
                    Por el momento no hay servicios registrados.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $services }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Fecha</th>
                                <th>Sucursal</th>
                                <th>Paciente</th>
                                <th>N° estudios</th>
                                <th>Costo</th>
                                <th>Impresa</th>
                                <th>N° RX</th>
                                <th>Nota</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="serviceItem in resourceList" class="table-resource__row" :key="serviceItem.id">
                                <td data-label="Fecha:">
                                    @{{ serviceItem.formated_date }}
                                </td>
                                <td data-label="Sucursal:">
                                    @{{ serviceItem.branch.name }}
                                </td>
                                <td data-label="Paciente:">
                                    @{{ serviceItem.patient }}
                                </td>
                                <td data-label="Paciente:">
                                    @{{ serviceItem.studies.length }}
                                </td>
                                <td data-label="Costo:">
                                    $ @{{ serviceItem.cost }}
                                </td>
                                <td data-label="Impresa:">
                                    @{{ serviceItem.print }}
                                </td>
                                <td data-label="N° RX:">
                                    @{{ serviceItem.no_rx }}
                                </td>
                                <td data-label="Nota:">
                                    <a class="btn btn-nowrap btn--sm btn--primary table-resource__button mr-2" :href="$root.path + '/notas/' + serviceItem.id">
                                        PDF
                                    </a>
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/servicios/' + serviceItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/servicios/eliminar/' + serviceItem.id"
                                        :resource-id="serviceItem.id"
                                        :options="{ onDelete: onResourceDelete }"
                                    >
                                        <img class="svg-icon" src="{{ url('img/svg/trash.svg')}}">
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
