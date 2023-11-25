@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Editar gasto')
@section('tab_title', 'Editar gasto | ' . config('app.name'))
@section('description', 'Editar gasto.')
@section('css_classes', 'dashboard')

@section('content')

    <section class="mb-16">
        <div class="dashboard-heading">
            <h1 class="dashboard-heading__title">
                Editar gasto
            </h1>
        </div>

        <div class="fluid-container mb-16">
            <p class="mb-12">
                @include('components.alert')
                <span class="color-link">«</span>
                <a href="{{ url('admin/gastos/') }}">Ver todo los gastos</a>
            </p>

            <base-form action="{{ url('admin/gastos/' . $expense->id . '/actualizar') }}" method="put"
                enctype="multipart/form-data" inline-template v-cloak>
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
                                        initial="{{ $expense->date }}"
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
                                    <select-field 
                                        name="branch_id" 
                                        v-model="fields.branch_id" 
                                        :options="{{ $branches }}"
                                        initial="{{ $expense->branch_id }}"
                                    >
                                    </select-field>
                                    <field-errors name="branch-id"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="expense">Gasto</label>
                                    <select-field 
                                        name="expense_id" 
                                        v-model="fields.expense_id" 
                                        :options="{{ $types_expense }}"
                                        initial="{{ $expense->type_expense_id }}"
                                    >
                                    </select-field>
                                    <field-errors name="expense"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-1/2">
                                <div class="form-control">
                                    <label for="cost">Costo</label>
                                    <text-field name="cost" v-model="fields.cost" initial="{{ $expense->amount }}"></text-field>
                                    <field-errors name="cost"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="form-control">
                            <label for="name">Nombre</label>
                            <text-field name="name" v-model="fields.name" initial="{{ $expense->person_name }}"></text-field>
                            <field-errors name="name"></field-errors>
                        </div>
                    </section>

                    <div class="text-center">
                        <form-button class="btn--blue--dashboard btn--wide">
                            Actualizar
                        </form-button>
                    </div>
                </form>
                </user-form>
        </div>
    </section>

@endsection
