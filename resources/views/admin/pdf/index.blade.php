<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="page_break mt-4">
        <img style="width: 160px;display: block;margin: 0 auto;" src="{{ url('img/imax-logo.png')}}" alt="">
        <div class="row">
            <div class="col-md-12">
                <h6>Reporte del periodo ({{ $start_date }} al {{ $end_date }})</h6>
                <table class="table table-bordered">
                    <caption>Lista de Servicios</caption>
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Paciente</th>
                        <th scope="col">Estudios</th>
                        <th scope="col">costo</th>
                        <th scope="col">Impresa</th>
                        <th scope="col">N° RX</th>

                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                        <tr>
                            <th scope="row">{{ $service->id }}</th>
                            <td>{{ $service->patient }}</td>
                            <td>{{ $service->list_studies }}</td>
                            <td>${{ $service->cost }}</td>
                            <td>{{ $service->print }}</td>
                            <td>{{ $service->no_rx }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Total</td>
                            <th>${{ $services->sum('cost')}}</th>
                            <th>-</th>
                            <th>{{ $services->sum('no_rx')}}</th>
                        </tr>
                    </tfoot>
                  </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <table class="table table-bordered">
                    <caption>Lista de gastos</caption>
                    <thead>
                      <tr>
                        <th scope="col">Gasto</th>
                        <th scope="col">Monto</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                        <tr>
                            <th scope="row">{{ $expense->type_expense->name }}</th>
                            <td>${{ $expense->amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <th>${{ $expenses->sum('amount')}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-2">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Total - Gasto</th>
                      </tr>
                    </thead>
                    <tbody>
                        <td>
                            ${{ $services->sum('cost') -  $expenses->sum('amount') }}
                        </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>