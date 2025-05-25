<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="page_break mt-4">
        <img style="width: 160px;display: block;margin: 0 auto;" src="{{ url('img/imax-logo.png')}}" alt="IMAX Logo">
        
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

        <div class="row mt-4">
            <table class="table table-bordered">
                <caption>Estudios Solicitados por el Doctor</caption>
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
    </div>
</body>
</html>
