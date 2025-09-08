@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Crear petición para eliminar registro')
@section('tab_title', 'Crear petición para eliminar registro | ' . config('app.name'))
@section('description', 'Crear petición para eliminar registro.')
@section('css_classes', 'dashboard')

@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Crear petición para eliminar registro
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
        </p>

            <base-form action="{{ url('admin/solicitud-eliminar/' . $id . '/' . $tipo) }}"
                enctype="multipart/form-data"
                inline-template
                v-cloak
            >
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Petición para eliminar
                        </h3>

                        <div class="md:row">
                            <div class="md:col-2/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="name">Nombre</label>
                                    <text-field name="name" v-model="fields.name" initial="{{ $data->display_name }}" disabled></text-field>
                                    <field-errors name="name"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-2/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="branch">Sucursal</label>
                                    <text-field name="branch" v-model="fields.branch" initial="{{ $data->branch->name }}" disabled></text-field>
                                    <field-errors name="branch"></field-errors>
                                </div>
                            </div>
                            <div class="md:col-2/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="reason">Razón</label>
                                    <text-area name="reason" cols="30" rows="10" v-model="fields.reason" maxlength="10000" initial=""></text-area>
                                    <field-errors name="reason"></field-errors>
                                </div>
                            </div>

                        </div>
                    </section>
                    <div class="text-center">
                        <form-button class="btn--success btn--wide">
                            Crear
                        </form-button>
                    </div>
                </form>
            </base-form>
    </div>
</section>

@endsection
