<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Doctores</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            font-size: 12px;
            margin: 10px;
        }
        table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed; /* Importante para que la tabla ajuste el ancho */
            word-wrap: break-word; /* Que las palabras largas se partan */
            word-break: break-all; /* Romper palabras si es necesario */
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            overflow-wrap: break-word;
            white-space: normal !important; /* Evita que el texto no se parta */
            vertical-align: middle;
            max-width: 100px; /* O ajusta el ancho máximo por columna */
        }
        /* Opcional: puedes ajustar anchos fijos para algunas columnas */
        th:nth-child(1), td:nth-child(1) { max-width: 120px; } /* Nombre */
        th:nth-child(2), td:nth-child(2) { max-width: 120px; } /* Apellido */
        th:nth-child(3), td:nth-child(3) { max-width: 150px; } /* Dirección */
        th:nth-child(4), td:nth-child(4) { max-width: 50px; }  /* C.P */
        th:nth-child(5), td:nth-child(5) { max-width: 150px; } /* Correo */
        th:nth-child(6), td:nth-child(6) { max-width: 100px; } /* Teléfono */
        th:nth-child(7), td:nth-child(7) { max-width: 80px; }  /* Cantidad servicios */
        th:nth-child(8), td:nth-child(8) { max-width: 100px; } /* Último servicio */
    </style>
</head>
<body>
    <div class="page_break mt-4">
        <img style="width: 160px; display: block; margin: 0 auto;" src="{{ url('img/imax-logo1.png') }}" alt="IMAX Logo">
        
        <div class="text-center my-3">
            <h3>Reporte General de Doctores</h3>
            @if($start_date && $end_date)
                <p>Filtrado desde {{ $start_date }} hasta {{ $end_date }}</p>
            @else
                <p>Historial completo</p>
            @endif

            @if($search)
                <p><strong>Búsqueda:</strong> "{{ $search }}"</p>
            @endif

            
            @php
                $sortLabels = [
                    'last_name' => 'Apellido',
                    'name' => 'Nombre',
                    'address' => 'Dirección',
                    'Correo electrónico' => 'email',
                    'Cp' => 'CP',
                    'last_service_date' => 'Último servicio',
                    'count_services' => 'Cantidad de servicios',
                ];
            @endphp

            @if($sort_by && $sort_dir)
                <p><strong>Ordenado por:</strong> 
                    {{ $sortLabels[$sort_by] ?? ucfirst($sort_by) }} 
                    ({{ $sort_dir == 'asc' ? 'Ascendente' : 'Descendente' }})
                </p>
            @endif

        </div>

        {{-- Doctores con servicios --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <caption>Listado de Doctores</caption>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>C.P</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Cantidad de servicios</th>
                        <th>Último servicio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withServices as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->last_name }}</td>
                            <td>{{ $doctor->address }}</td>
                            <td>{{ $doctor->cp }}</td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->tel }}</td>
                            <td>{{ $doctor->count_services }}</td>
                            <td>{{ $doctor->last_service_date ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No se encontraron doctores con servicios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- Doctores sin servicios --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <caption>Doctores sin Servicios</caption>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>C.P</th>
                        <th>Correo electrónico</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withoutServices as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->last_name }}</td>
                            <td>{{ $doctor->address }}</td>
                            <td>{{ $doctor->cp }}</td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->tel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No se encontraron doctores sin servicios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
