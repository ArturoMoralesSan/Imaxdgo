<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Corte General Diario</title>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body { font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="container mt-4">

    {{-- LOGO --}}
    <img style="width:160px; display:block; margin:0 auto;"
         src="https://imaxdgo.com/img/imax-logo1.png">

    <h5 class="text-center mt-3">
        Corte General Diario
    </h5>

    {{-- TABLA RESUMEN --}}
    <table class="table table-bordered mt-4">
        <thead class="thead-light">
        <tr>
            <th>Sucursal</th>
            <th>Fecha</th>
            <th class="text-right">Efectivo</th>
            <th class="text-right">Tarjeta</th>
            <th class="text-right">Transferencia</th>
            <th class="text-right">Gastos</th>
            <th class="text-right">Efectivo a entregar</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($closures as $closure)
            <tr>
                <td>{{ $closure->branch->name }}</td>

                <td class="text-center">
                    {{ \Carbon\Carbon::parse($closure->closed_at)->format('d/m/Y H:i') }}
                </td>

                <td class="text-right">
                    ${{ number_format($closure->cash_total, 2) }}
                </td>

                <td class="text-right">
                    ${{ number_format($closure->card_total, 2) }}
                </td>

                <td class="text-right">
                    ${{ number_format($closure->transfer_total, 2) }}
                </td>

                <td class="text-right">
                    ${{ number_format($closure->expenses, 2) }}
                </td>

                <td class="text-right font-weight-bold">
                    ${{ number_format($closure->total_delivery, 2) }}
                </td>
            </tr>
        @endforeach
        </tbody>

        {{-- TOTALES GENERALES --}}
        <tfoot class="font-weight-bold">
        <tr>
            <td colspan="2">TOTALES</td>

            <td class="text-right">
                ${{ number_format($closures->sum('cash_total'), 2) }}
            </td>

            <td class="text-right">
                ${{ number_format($closures->sum('card_total'), 2) }}
            </td>

            <td class="text-right">
                ${{ number_format($closures->sum('transfer_total'), 2) }}
            </td>

            <td class="text-right">
                ${{ number_format($closures->sum('expenses'), 2) }}
            </td>

            <td class="text-right">
                ${{ number_format($closures->sum('total_delivery'), 2) }}
            </td>
        </tr>
        </tfoot>
    </table>

</div>

</body>
</html>
