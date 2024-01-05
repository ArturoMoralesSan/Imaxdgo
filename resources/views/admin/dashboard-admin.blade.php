@extends('layout.dashboard-master')

{{-- Metadata --}}
@section('meta.title', 'Panel de administración' )
@section('meta.tab_title', 'Panel de administración | ' . config('app.name'))
@section('css_classes', 'dashboard')

@section('content')
    <div class="dashboard-heading">
        <div class="md:row justify-between">
            <div class="md:col-1/2">
                <h1 class="dashboard-heading__title">
                    Panel de administración
                </h1>
            </div>
            <div class="md:col-1/2 d-flex items-center">
                <form-between-date-search
                        selectedstart="{{ app('request')->input('start_date') }}"
                        selectedend="{{ app('request')->input('end_date') }}"
                    >
                    <template slot="svg-search">
                        <img class="search-form_icon--55" src="{{ url('img/svg/search.svg') }}" alt="">
                    </template>
                </form-between-date-search>
            </div>
        </div>
        
        <div>
            
        </div>
    </div>

    <div class="fluid-container mb-16">
        @include('components.alert')
        <section class="db-panel">
            <div class="row">
                <div class="column-statistics">
                   <strong>
                    Hoy <br>
                    {{ $string_date }}
                   </strong> 
                </div>
                <div class="column-statistics">
                    <strong>${{ $ingreso }}</strong> <br>
                    Ingresos
                </div>
                <div class="column-statistics mb-0">
                    <strong>${{ $gasto }}</strong> <br>
                    Gastos
                </div>
                <div class="column-statistics mb-0">
                    <strong>{{ $servicesCount }}</strong> <br>
                    Órdenes
                </div>
            </div>
        </section>
        <section class="db-panel">
            <h3 class="db-panel__title">
                Ingresos
            </h3>
            <canvas id="canvas" class="graph-statistics-income"></canvas>
        </section>
        <section class="db-panel">
            <div class="row">
                <div class="column-statistics-1-3 ">
                    <strong>${{ $CostbyServices }}</strong> <br>
                    Ingresos
                </div>
                <div class="column-statistics-1-3 ">
                    <strong>${{ $expensesCount }}</strong> <br>
                    Gastos
                </div>
                <div class="column-statistics-1-3 mb-0">
                    <strong> {{ $ordersAll }}</strong> <br>
                    Órdenes
                </div>
            </div>
        </section>
        <div class="md:row">
            <div class="md:col-1/2">
                <section class="db-panel">
                    <h3 class="db-panel__title">
                        Metodos de pago
                    </h3>
                    <canvas id="canvaspayments" class="graph-statistics-pay"></canvas>
                </section>
            </div>
            <div class="md:col-1/2">
                <section class="db-panel">
                    <h3 class="db-panel__title">
                        Ingresos por sucursal
                    </h3>
                    <resource-table :breakpoint="800" :model="{{ $branches }}" inline-template>

                        <table class="table size-caption mx-auto md:table--responsive">
                            <thead>
                                <tr class="table-resource__headings">
                                    <th>Sucursal</th>
                                    <th>cantidad</th>
                                    <th>Monto</th>
                                    <th>Reporte</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="branchItem in resourceList" class="table-resource__row" :key="branchItem.id">
                                    <td data-label="Estudio:">
                                        @{{ branchItem.name }}
                                    </td>
                                    <td data-label="Cantidad:">
                                        @{{ branchItem.count_services }}
                                    </td>
                                    <td data-label="Monto:">
                                        $@{{ branchItem.amount_services }}
                                    </td>
                                    <td data-label="Reporte:">
                                        <link-pdf 
                                            :branchid="branchItem.id" 
                                            startdate="{{ app('request')->input('start_date') }}"
                                            enddate="{{ app('request')->input('end_date') }}">
                                        </link-pdf>                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </resource-table>
                </section>
            </div>
        </div>
        <div class="md:row">
            <div class="md:col-2/3">
                <section class="db-panel">
                    <h3 class="db-panel__title">
                        Estudios más populares
                    </h3>
                    <resource-table :breakpoint="800" :model="{{ $studiesCount }}" inline-template>

                        <table class="table size-caption mx-auto md:table--responsive">
                            <thead>
                                <tr class="table-resource__headings">
                                    <th>Estudio</th>
                                    <th>cantidad</th>
                                    
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="studyItem in resourceList" class="table-resource__row" :key="studyItem.id">
                                    <td data-label="Estudio:">
                                        @{{ studyItem.name }}
                                    </td>
                                    <td data-label="Cantidad:">
                                        @{{ studyItem.services_count }}
                                    </td>
                            
                                </tr>
                            </tbody>
                        </table>

                    </resource-table>
                </section>
            </div>
        </div>
    </div>

    <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.js"></script>
    <script type="application/javascript">
        var amountPerDay = <?php echo $days; ?>;
        var amountPerpayment = <?php echo $servicesPerPayments; ?>;

        var lineChartData = {
            labels: Object.keys(amountPerDay),
            datasets: [{
                label: 'Total',
                fill: true,
                backgroundColor: ['rgba(64, 157, 205, 0.3)'],
                borderColor: '#36A2EB',
                data: Object.values(amountPerDay)
            }]
        };

        var barChartDataPayment = {
            labels: Object.keys(amountPerpayment),
            datasets: [{
                label: 'Total',
                backgroundColor: ['#9BD0F5'],
                borderColor: '#36A2EB',
                data: Object.values(amountPerpayment)
            }]
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, {
                type: 'line',
                data: lineChartData,
                options: {
                    responsive: true,                    title: {
                        display: true,
                        text: 'Total de Ingresos'
                    }
                }
            });
            var ctxb = document.getElementById("canvaspayments").getContext("2d");
            window.myLineb = new Chart(ctxb, {
                type: 'bar',
                data: barChartDataPayment,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Total de Ingresos'
                    }
                }
            });
        };
    </script>

@endsection
