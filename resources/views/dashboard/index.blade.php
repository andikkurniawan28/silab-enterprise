@extends('template.sneat.master')

@section('dashboard-active')
    {{ 'active' }}
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4>{{ ucwords(str_replace(' ', '_', 'dashboard')) }}</h4>

        <div class="row">

            @foreach ($setup->monitorings as $monitoring)
                @if ($monitoring->method == 'Trendline')
                    <div class="col-lg-6 mb-4 order-0">
                        <div class="card bg-primary">
                            <div class="d-flex align-items-end row">
                                <div class="col-sm-12">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-white">
                                            {{ $monitoring->method }}
                                            {{ $monitoring->material->name }}
                                        </h6>
                                        <h6 class="card-title text-white">
                                            {{ $monitoring->parameter->name }}
                                        </h6>
                                        <br>
                                        <canvas id="chart-{{ $monitoring->id }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-3 mb-4 order-0">
                        <div class="card bg-primary">
                            <div class="d-flex align-items-end row">
                                <div class="col-sm-12">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-white">
                                            {{ $monitoring->method }}
                                            {{ $monitoring->material->name }}
                                        </h6>
                                        <h6 class="card-title text-white">
                                            {{ $monitoring->parameter->name }}
                                        </h6>
                                        <br>
                                        <h2 class="mb-4 text-white">
                                            @if ($monitoring->data != null)
                                                {{ number_format($monitoring->data, $monitoring->parameter->behind_decimal) }}
                                                @php echo $monitoring->parameter->measurement_unit->name; @endphp
                                            @else
                                                -
                                            @endif
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
    <!-- / Content -->
@endsection

@section('additional_script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($setup->monitorings as $monitoring)
                @if ($monitoring->method == 'Trendline')
                    const ctx{{ $monitoring->id }} = document.getElementById('chart-{{ $monitoring->id }}')
                        .getContext('2d');
                    const labels{{ $monitoring->id }} = {!! json_encode(
                        $monitoring->data->pluck('created_at')->map(function ($date) {
                            return \Carbon\Carbon::parse($date)->format('d-m-Y H:i');
                        }),
                    ) !!};
                    const dataValues{{ $monitoring->id }} = {!! json_encode($monitoring->data->pluck($monitoring->parameter->name)) !!};

                    new Chart(ctx{{ $monitoring->id }}, {
                        type: 'line',
                        data: {
                            labels: labels{{ $monitoring->id }},
                            datasets: [{
                                label: '{{ $monitoring->parameter->name }}',
                                data: dataValues{{ $monitoring->id }},
                                borderColor: 'white',
                                backgroundColor: 'rgba(255, 255, 255, 0.2)',
                                fill: false,
                            }],
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    type: 'category',
                                    labels: labels{{ $monitoring->id }},
                                    ticks: {
                                        color: 'white',
                                    },
                                },
                                y: {
                                    beginAtZero: true,
                                    min: Math.min(...
                                    dataValues{{ $monitoring->id }}) - 0.5, // Menentukan nilai minimum dari data
                                    max: Math.max(...
                                    dataValues{{ $monitoring->id }}) + 0.5, // Menentukan nilai maksimum dari data
                                    ticks: {
                                        color: 'white',
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: 'white',
                                    },
                                },
                            },
                        },
                    });
                @endif
            @endforeach
        });
    </script>
@endsection
