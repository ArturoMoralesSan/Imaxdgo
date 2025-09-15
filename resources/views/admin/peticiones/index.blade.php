@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Peticiones')
@section('tab_title', 'Peticiones | ' . config('app.name'))
@section('description', 'Lista de peticiones.')
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Peticiones
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $requests->count() }} peticiones registradas.
        </p>
    </div>

    <div class="fluid-container mb-16">
        @include('components.alert')
        <section class="db-panel">
            <h3 class="db-panel__title">
                Lista de peticiones
            </h3>

            @if (! $requests->count())
                <p class="text-center py-1">
                    Por el momento no hay peticiones registradas.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $requests }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Sucursal</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Razón</th>
                                <th>Estado</th>
                                <th>Solicitado</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="requestItem in resourceList" class="table-resource__row" :key="requestItem.id">
                                <td data-label="ID:">
                                    @{{ requestItem.record_id }}
                                </td>
                                <td data-label="Nombre:">
                                    @{{ requestItem.name }}
                                </td>
                                <td data-label="Sucursal:">
                                    @{{ requestItem.branch }}
                                </td>
                                <td data-label="Tipo:">
                                    @{{ requestItem.type }}
                                </td>
                                <td data-label="Valor:">
                                   $ @{{ requestItem.value }}
                                </td>
                                <td data-label="Razón:">
                                    @{{ requestItem.reason }}
                                </td>
                                <td data-label="Estado:">
                                    @{{ requestItem.status }}
                                </td>
                                <td data-label="Solicitado:">
                                    @{{ requestItem.deleted_by }}
                                </td>
                                <td data-label="Fecha:">
                                    @{{ requestItem.formated_date }}
                                </td>
                                <td class="table-resource__actions" data-label="Acciones:">
                                    <approve-button 
                                        v-if="requestItem.status == 'pendiente'"
                                        class="btn--primary table-resource__button" 
                                        :url="$root.path + '/admin/peticiones/aprobar/' + requestItem.id"
                                        :resource-id="requestItem.id"
                                    >
                                        <img class="svg-icon" src="{{ url('img/svg/aproved.svg')}}">
                                        Aprobar
                                    </approve-button>
                                    
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </resource-table>

            @endif

        </section>
    </div>
@endsection
