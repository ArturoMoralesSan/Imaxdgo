@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('meta.title', 'Panel de administración' )
@section('meta.tab_title', 'Panel de administración | ' . config('app.name'))
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <div class="md:row justify-between">
            <div class="md:col-1/2">
                <h1 class="dashboard-heading__title">
                    Panel de administración
                </h1>
            </div>
            <div class="md:col-1/2 d-flex items-center">
                <form-between-date-search
                        selectedstart="{{ app('request')->input('start_date') }}"
                        selectedend="{{ app('request')->input('end_date') }}"
                    >
                    <template slot="svg-search">
                        <img class="search-form_icon--55" src="{{ url('img/svg/search.svg') }}" alt="">
                    </template>
                </form-between-date-search>
            </div>
        </div>
    </div>

    <div class="fluid-container">
        <section class="db-panel">
            <h3 class="db-panel__title">
                Ingresos de sucursal
            </h3>
            <resource-table :breakpoint="800" :model="{{ $branches }}" inline-template>

                <table class="table size-caption mx-auto md:table--responsive">
                    <thead>
                        <tr class="table-resource__headings">
                            <th>Sucursal</th>
                            <th>cantidad</th>
                            <th>Monto</th>
                            <th>Reporte</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="branchItem in resourceList" class="table-resource__row" :key="branchItem.id">
                            <td data-label="Estudio:">
                                @{{ branchItem.name }}
                            </td>
                            <td data-label="Cantidad:">
                                @{{ branchItem.count_services }}
                            </td>
                            <td data-label="Monto:">
                                $@{{ branchItem.amount_services }}
                            </td>
                            <td data-label="Reporte:">
                                <link-pdf 
                                    :branchid="branchItem.id" 
                                    startdate="{{ app('request')->input('start_date') }}"
                                    enddate="{{ app('request')->input('end_date') }}">
                                </link-pdf>                                    
                            </td>
                        </tr>
                    </tbody>
                </table>
            </resource-table>
        </section>
    </div>

@endsection
