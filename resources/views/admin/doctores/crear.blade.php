@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Agregar doctor')
@section('tab_title', 'Agregar doctor | ' . config('app.name'))
@section('description', 'Agregar doctor.')
@section('css_classes', 'dashboard')
@section('content')

<section class="mb-16">
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Agregar doctor
        </h1>
    </div>

    <div class="fluid-container mb-16">
        <p class="mb-12">
            @include('components.alert')
            <span class="color-link">«</span>
            <a href="{{ url('admin/doctores/') }}">Ver todos los doctores</a>
        </p>

            <base-form action="{{ url('admin/doctores/crear') }}"
                enctype="multipart/form-data"
                inline-template
                v-cloak
            >
                <form>
                    <section class="db-panel">
                        <h3 class="db-panel__title">
                            Datos del doctor
                        </h3>

                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="name">Nombre</label>
                                    <text-field name="name" v-model="fields.name" maxlength="80" initial=""></text-field>
                                    <field-errors name="name"></field-errors>

                                </div>
                            </div>
                            <div class="md:col-1/2">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="last_name">Apellido</label>
                                    <text-field name="last_name" v-model="fields.last_name" maxlength="80" initial=""></text-field>
                                    <field-errors name="last_name"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row mb-4">
                            <div class="md:col-2/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="address">Dirección</label>
                                    <text-field name="address" v-model="fields.address" maxlength="200" initial=""></text-field>
                                    <field-errors name="address"></field-errors>

                                </div>
                            </div>
                            <div class="md:col-1/3">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="cp">C.P</label>
                                    <text-field name="cp" v-model="fields.cp" maxlength="80" initial=""></text-field>
                                    <field-errors name="cp"></field-errors>
                                </div>
                            </div>
                        </div>
                        <div class="md:row mb-4">
                            <div class="md:col-1/2">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="email">Correo electrónico</label>
                                    <text-field name="email" v-model="fields.email" maxlength="80" initial=""></text-field>
                                    <field-errors name="email"></field-errors>

                                </div>
                            </div>
                            <div class="md:col-1/2">
                                {{-- nombres --}}
                                <div class="form-control">
                                    <label for="tel">Teléfono</label>
                                    <text-field name="tel" v-model="fields.tel" maxlength="80" initial=""></text-field>
                                    <field-errors name="tel"></field-errors>
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
