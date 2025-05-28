@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Doctores')
@section('tab_title', 'Doctores | ' . config('app.name'))
@section('description', 'Lista de doctores.')
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Doctores
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $doctors->count() }} doctores registrados.
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
                Lista de doctores
            </h3>

            @if (! $doctors->count())
                <p class="text-center py-1">
                    Por el momento no hay doctores registrados.
                </p>
            @else

                <resource-table-sortable 
                    :breakpoint="800"
                    :model='@json($doctors->items())'
                    inline-template
                    sort-by="{{ $sortKey }}"
                    sort-dir="{{ $sortAsc ? 'asc' : 'desc' }}"
                    search="{{ $search ?? '' }}"
                >
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th @click="sortByColumn('name')">
                                    Nombre
                                    <span v-if="isSorted('name')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('last_name')">
                                    Apellido
                                    <span v-if="isSorted('last_name')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('address')">
                                    Dirección
                                    <span v-if="isSorted('address')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('cp')">
                                    C.P
                                    <span v-if="isSorted('cp')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('email')">
                                    Correo electrónico
                                    <span v-if="isSorted('email')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('tel')">
                                    Teléfono
                                    <span v-if="isSorted('tel')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('count_services')">
                                    Cantidad de servicios
                                    <span v-if="isSorted('count_services')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th @click="sortByColumn('last_service_date')">
                                    Último servicio
                                    <span v-if="isSorted('last_service_date')">
                                        @{{ sortAsc ? '↑' : '↓' }}
                                    </span>
                                </th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="doctorsItem in model" :key="doctorsItem.id" class="table-resource__row">
                                <td data-label="Nombre">@{{ doctorsItem.name }}</td>
                                <td data-label="Apellido">@{{ doctorsItem.last_name }}</td>
                                <td data-label="Dirección">@{{ doctorsItem.address }}</td>
                                <td data-label="C.P">@{{ doctorsItem.cp }}</td>
                                <td data-label="Correo electrónico">@{{ doctorsItem.email }}</td>
                                <td data-label="Teléfono">@{{ doctorsItem.tel }}</td>
                                <td data-label="Cantidad de servicios">@{{ doctorsItem.count_services }}</td>
                                <td data-label="Último servicio">@{{ doctorsItem.last_service_date }}</td>
                                <td class="table-resource__actions" data-label="Acciones">
                                    <a :href="$root.path + '/admin/doctores/' + doctorsItem.id + '/editar'" class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg') }}">
                                        Editar
                                    </a>
                                    <delete-button
                                        class="btn--danger table-resource__button"
                                        :url="$root.path + '/admin/doctores/eliminar/' + doctorsItem.id"
                                        :resource-id="doctorsItem.id"
                                        :options="{ onDelete: onResourceDelete }"
                                    >
                                        <img class="svg-icon" src="{{ url('img/svg/trash.svg') }}">
                                        Eliminar
                                    </delete-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </resource-table-sortable>

                {{ $doctors->links('layout.pagination') }}

            @endif

        </section>
    </div>
@endsection
