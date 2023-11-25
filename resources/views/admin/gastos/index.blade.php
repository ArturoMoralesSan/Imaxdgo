@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Gastos')
@section('tab_title', 'Gastos | ' . config('app.name'))
@section('description', 'Gastos.')
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Gastos
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $expenses->count() }} gastos registrados.
        </p>
    </div>

    <div class="fluid-container mb-16">
        @include('components.alert')
        <section class="db-panel">
            <h3 class="db-panel__title">
                Gastos
            </h3>

            @if (! $expenses->count())
                <p class="text-center py-1">
                    Por el momento no hay gastos registrados.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $expenses }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Fecha</th>
                                <th>Sucursal</th>
                                <th>Nombre</th>
                                <th>Gasto</th>
                                <th>Costo</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="expensesItem in resourceList" class="table-resource__row" :key="expensesItem.id">
                                <td data-label="Fecha:">
                                    @{{ expensesItem.formated_date }}
                                </td>
                                <td data-label="Sucursal:">
                                    @{{ expensesItem.branch.name }}
                                </td>
                                <td data-label="Nombre:">
                                    @{{ expensesItem.person_name }}
                                </td>
                                <td data-label="Gasto:">
                                    @{{ expensesItem.type_expense.name }}
                                </td>
                                <td data-label="Costo:">
                                    @{{ expensesItem.amount }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <a class="btn btn-nowrap btn--sm btn--blue table-resource__button mr-2" :href="$root.path + '/admin/gastos/' + expensesItem.id + '/editar' ">
                                        <img class="svg-icon" src="{{ url('img/svg/edit.svg')}}">
                                        Editar
                                    </a>
                                    <delete-button class="btn--danger table-resource__button" :url="$root.path + '/admin/gastos/eliminar/' + expensesItem.id"
                                        :resource-id="expensesItem.id"
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
