<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Servicios</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <style>
        body {
            font-size: 12px;
        }
        h6 {
            margin-top: 20px;
        }
        caption {
            caption-side: top;
            font-weight: bold;
            font-size: 14px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="page_break mt-4">
        <img style="width: 160px; display: block; margin: 0 auto;" src="{{ url('img/imax-logo1.png') }}" alt="IMAX Logo">
        
        <div class="text-center my-3">
            <h3>Reporte del Dr. {{ $doctorName }}</h3>
            <h4>
                Reporte 
                @if($branchName)
                    por Sucursal: {{ $branchName }}
                @else
                    Global
                @endif
            </h4>       
            <p>Periodo: {{ $start_date }} al {{ $end_date }}</p>

            @isset($totalServices)
                <p><strong>Total de pacientes:</strong> {{ $totalServices }}</p>
            @endisset
        </div>

        {{-- Tabla de pacientes --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <caption>Pacientes Atendidos</caption>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Estudio</th>
                        <th>Sucursal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td>{{ $patient['patient_name'] }}</td>
                            <td>{{ $patient['study_name'] }}</td>
                            <td>{{ $patient['branch_name'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se encontraron pacientes en este periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabla de estudios solicitados --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <caption>Total de estudios</caption>
                <thead>
                    <tr>
                        <th>Estudio</th>
                        <th>Cantidad Solicitada</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($studiesCount as $study)
                        <tr>
                            <td>{{ $study['study_name'] }}</td>
                            <td>{{ $study['count'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No se encontraron estudios solicitados para este periodo.</td>
                        </tr>
                    @endforelse
                    @if ($studiesCount->count())
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $studiesCount->sum('count') }}</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Conteo por sucursal --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <caption>Pacientes por Sucursal (únicos)</caption>
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Cantidad de Pacientes Únicos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($branchesCount as $branch)
                        <tr>
                            <td>{{ $branch['branch_name'] }}</td>
                            <td>{{ $branch['count'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No hay datos de sucursales para este periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Último paciente --}}
        <div class="mt-4">
            <h6><strong>Último paciente atendido:</strong></h6>
            <p>Fecha: {{ $lastPatientDate }}</p>
            <p>Sucursal: {{ $lastPatientBranch }}</p>
        </div>
    </div>
</body>
</html>
