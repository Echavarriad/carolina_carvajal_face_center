@extends('admin.layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{-- {{$ordersCount}} --}}</h3>

                        <p>Total pedidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <div class="links-order">
                        <a href="{{-- {{ route('order.index') }} --}}" class="small-box-footer">Ver Todos <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{-- {{$usersCount}} --}}</h3>

                        <p>Usuarios logueados</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                    <a href="{{-- {{ route('customer.index') }} --}}" class="small-box-footer">Ver más <i
                            class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{-- {{ $cartsCount }} --}}</h3>
                        <p>Carritos abandonados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cart-arrow-down"></i>
                    </div>
                    <a href="{{-- {{ route('cart.index') }} --}}" class="small-box-footer">Ver más <i
                            class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box ">
                    <div class="box-header with-border">
                        <h3 class="box-title">En 30 días</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body" style="padding:  20px;">
                        <div class="row">
                            <canvas id="chart-days-in-month" width="700" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box ">
                    <div class="box-header with-border">
                        <h3 class="box-title">En 12 meses</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body" style="padding:  20px;">
                        <div class="row">
                            <canvas id="chartjs-year" width="700" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <h3 class="box-title">Ultimos pedidos</h3>
                        {{--  @include('admin.order.list_orders') --}}

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <h3 class="box-title">Usuarios registrados</h3>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Celular</th>
                                    <th>Último Login</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{--  @foreach ($customers as $customer)
                                    <tr>
                                         <td>{{ $customer->name . ' ' . $customer->lastname }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->mobile }}</td>
                                        <td>{{ $customer->last_login }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- @push('js')
    <script src="{{ asset('mng/Chart.js/dist/Chart.bundle.min.js') }}"></script>
    <script>
        function format_number(n) {
            return n.toFixed(0).replace(/./g, function(c, i, a) {
                return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
            });
        }

        $(document).ready(function($) {
            var ctx = document.getElementById('chart-days-in-month').getContext('2d');
            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'bar',

                // The data for our dataset
                data: {
                    // type: 'category',
                    labels: {!! $arrDays !!},
                    datasets: [


                        {
                            label: "Total monto",
                            backgroundColor: '#CF50E7',
                            borderColor: "#04947F",
                            borderCapStyle: 'square',
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: "yellow",
                            pointHoverBorderColor: "brown",
                            data: {!! $arrTotalsAmount !!},
                            showLine: true, // disable for a single dataset,
                            yAxisID: "y-axis-gravity",
                            fill: false,
                            type: 'line',
                            lineTension: 0.1,
                        },
                        {
                            label: "Total pedido",
                            backgroundColor: 'rgb(138, 199, 214)',
                            borderColor: 'rgb(138, 199, 214)',
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: "brown",
                            pointHoverBorderColor: "yellow",
                            data: {!! $arrTotalsOrder !!},
                            showLine: true, // disable for a single dataset,
                            yAxisID: "y-axis-density",
                            spanGaps: true,
                            lineTension: 0.1,

                        },

                    ]
                },

                // Configuration options go here
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 0,
                            bottom: 0
                        }
                    },
                    scales: {
                        yAxes: [{
                                position: "left",
                                id: "y-axis-density",
                                ticks: {
                                    beginAtZero: true,
                                    max: {!! $max_order !!} + 5,
                                    min: 0,
                                    stepSize: 2,
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total pedido',
                                    fontSize: 15,

                                }
                            },
                            {
                                position: "right",
                                id: "y-axis-gravity",
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(label, index, labels) {
                                        return format_number(label);
                                    },
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total monto',
                                    fontSize: 15
                                }
                            }
                        ]
                    },

                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                if (label) {
                                    label += ': ';
                                }
                                label += format_number(tooltipItem.yLabel);
                                return label;
                            }
                        }
                    }
                }
            });


            var ctx2 = document.getElementById('chartjs-year').getContext('2d');
            var chart2 = new Chart(ctx2, {
                // The type of chart we want to create
                type: 'bar',

                // The data for our dataset
                data: {
                    "labels": {!! $months1 !!},
                    "datasets": [{
                            "label": "Total monto",
                            "data": {!! $arrTotalsAmount_year !!},
                            "fill": false,
                            "backgroundColor": [
                                "rgba(191, 25, 232, 0.2)",
                                "rgba(191, 25, 232, 0.2)",
                                "rgba(191, 25, 232, 0.2)",
                                "rgba(191, 25, 232, 0.2)",
                                "rgba(255, 99, 132, 0.2)",
                                "rgba(255, 159, 64, 0.2)",
                                "rgba(255, 205, 86, 0.2)",
                                "rgba(75, 192, 192, 0.2)",
                                "rgba(54, 162, 235, 0.2)",
                                "rgba(153, 102, 255, 0.2)",
                                "rgba(201, 203, 207, 0.2)",
                                "rgba(181, 147, 50, 0.2)",
                                "rgba(232, 130, 81, 0.2)",
                            ],
                            "borderColor": [
                                "rgb(191, 25, 232)",
                                "rgb(191, 25, 232)",
                                "rgb(191, 25, 232)",
                                "rgb(191, 25, 232)",
                                "rgb(255, 99, 132)",
                                "rgb(255, 159, 64)",
                                "rgb(255, 205, 86)",
                                "rgb(75, 192, 192)",
                                "rgb(54, 162, 235)",
                                "rgb(153, 102, 255)",
                                "rgb(201, 203, 207)",
                                "rgb(181, 147, 50)",
                                "rgb(232, 130, 81)",
                            ],
                            "borderWidth": 1,
                            type: "bar",
                        },
                        {
                            "label": "Linea total monto",
                            "data": {!! $arrTotalsAmount_year !!},
                            "fill": false,
                            "backgroundColor": "red",
                            "borderColor": "red",
                            "borderWidth": 1,
                            type: "line",
                        }
                    ]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 0,
                            bottom: 0
                        }
                    },

                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                if (label) {
                                    label += ': ';
                                }
                                label += format_number(tooltipItem.yLabel);
                                return label;
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            position: "left",
                            // id: "y-axis-amount",
                            ticks: {
                                beginAtZero: true,
                                callback: function(label, index, labels) {
                                    return format_number(label);
                                },
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Monto',
                                fontSize: 15
                            }
                        }]
                    },
                },

            });

        });
    </script>
@endpush --}}
