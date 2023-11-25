@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('title', 'Usuarios')
@section('tab_title', 'Usuarios | ' . config('app.name'))
@section('description', 'Lista de noticias publicadas.')
@section('class', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <h1 class="dashboard-heading__title">
            Artistas
        </h1>

        <p class="dashboard-heading__caption">
            Hay {{ $artists->count() }} artistas registrados.
        </p>
    </div>

    <div class="fluid-container mb-16">
        @alert(['class' => 'alert--has-icon size-caption'])
        @endalert

        <div class="row">
            <div class=" col md:col-1/2 mb-4">
                <label for="activity">Actividad principal</label>
                <select-filter
                    name="activity"
                    selected="{{ app('request')->input('activity') }}"
                    :options="{{ $activities }}"
                >
                </select-filter>
            </div>
            <div class="col md:col-1/2 mb-4">
                <label for="category">Catergoía</label>
                <select-filter
                        name="category"
                        selected="{{ app('request')->input('category') }}"
                        :options="{{ $categories }}"
                    >
                    </select-filter>
            </div>
        </div>

        <section class="db-panel">
            <h3 class="db-panel__title">
                Lista de artistas
            </h3>

            @if (! $artists->count())
                <p class="text-center py-1">
                    Por el momento no hay artistas registrados.
                </p>
            @else

                <resource-table :breakpoint="800" :model="{{ $artists }}" inline-template>
                    <table class="table size-caption mx-auto mb-16 md:table--responsive">
                        <thead>
                            <tr class="table-resource__headings">
                                <th>Nombre completo</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Celular</th>
                                <th>Actividad</th>
                                <th>Categoría</th>
                                <th>Especialidad</th>
                                <th class="pr-4">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="userItem in resourceList" class="table-resource__row" :key="userItem.id">
                                <td data-label="Nombre completo:">
                                    @{{ userItem.user.full_name }}
                                </td>
                                <td data-label="Correo electrónico:">
                                    @{{ userItem.user.email }}
                                </td>
                                <td data-label="Teléfono:">
                                    @{{ userItem.telephone }}
                                </td>
                                <td data-label="Celular:">
                                    @{{ userItem.cellphone }}
                                </td>
                                <td data-label="Actividad:">
                                    @{{ userItem.activity.name }}
                                </td>
                                <td data-label="Categoría:">
                                    @{{ userItem.category.name }}
                                </td>
                                 <td data-label="Especialidad:">
                                    @{{ userItem.speciality }}
                                </td>

                                <td class="table-resource__actions" data-label="Acciones:">
                                    <delete-button  class="btn-flex" v-if="userItem.id != {{ Auth::user()->id }}" :url="$root.path + '/admin/artistas/eliminar/' + userItem.id"
                                        :resource-id="userItem.id"
                                        :options="{ onDelete: onResourceDelete }"
                                    >
                                        <img style="width: 15px;" class="svg-icon" src="{{ url('img/svg/trash.svg')}}">
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
