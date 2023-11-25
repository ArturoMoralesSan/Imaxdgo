@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Agregar Gastos')
@section('tab_title', 'Agregar Gastos | ' . config('app.name'))
@section('description', 'Agregar Gastos.')
@section('css_classes', 'dashboard')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Agregar gasto
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/gastos/') }}">Ver todos los gastos</a>
            </p>

            <base-form action="{{ url('admin/gastos/crear') }}" enctype="multipart/form-data" inline-template v-cloak>
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Datos del gasto
                        </h3>

                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="date">Fecha</label>
                                    <date-field name="date"
                                        disabled
                                        v-model="fields.date"
                                        :initial="new Date().toLocaleDateString('en-CA')"
                                        :aria-describedby="supportsDates ? '' : 'start-date-description'"
                                    ></date-field>
                                    <field-errors name="date"></field-errors>

                                    <p v-if="! supportsDates" id="start-date-description" class="description">
                                        Formato: dd/mm/aaaa
                                    </p>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="branch">Sucursal</label>
                                    <select-field class="form-select" name="branch_id" v-model="fields.branch_id"
                                        :options="{{ $branches }}">
                                    </select-field>
                                    <field-errors name="branch_id"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="expense">Gasto</label>
                                    <select-field class="form-select" name="expense_id" v-model="fields.expense_id"
                                        :options="{{ $types_expense }}">
                                    </select-field>
                                    <field-errors name="expense_id"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="cost">Costo</label>
                                    <text-field name="cost" v-model="fields.cost"></text-field>
                                    <field-errors name="cost"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="form-control">
                            <label for="name">Nombre</label>
                            <text-field name="name" v-model="fields.name"></text-field>
                            <field-errors name="name"></field-errors>
                        </div>
                    </section>
                    <div class="text-center">
                        <form-button class="btn--success btn--wide">
                            Agregar
                        </form-button>
                    </div>
                </form>
            </base-form>
        </div>
    </section>

@endsection
