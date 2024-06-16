@extends('template.sneat.master')

@section('dashboard-active')
    {{ 'active' }}
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4>{{ ucwords(str_replace(' ', '_', 'dashboard')) }}</h4>

        <div class="row">
            <!-- Form with station dropdown and date & time inputs -->
            <form id="filterForm" action="{{ route('change_datetime') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="from_datetime" class="form-label">From</label>
                        <input type="datetime-local" class="form-control" id="from_datetime" name="from_datetime"
                            value="{{ session('from_datetime') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="to_datetime" class="form-label">To</label>
                        <input type="datetime-local" class="form-control" id="to_datetime" name="to_datetime"
                            value="{{ session('to_datetime') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" id="filterButton">Filter</button>
                <br><br>
            </form>

            @foreach ($setup->monitorings as $monitoring)
                @if ($monitoring->method == 'Trendline')
                    <div class="col-lg-12 mb-4 order-0">
                        <div class="card bg-primary">
                            <div class="d-flex align-items-end row">
                                <div class="col-sm-12">
                                    <div class="card-body text-center">
                                        <h4 class="card-title text-white">
                                            {{ $monitoring->parameter->name }}
                                            {{ $monitoring->material->name }}
                                            {{ $monitoring->method }}
                                        </h4>
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
                                        <h4 class="card-title text-white">
                                            {{ $monitoring->parameter->name }}
                                            {{ $monitoring->material->name }}
                                            {{ $monitoring->method }}
                                        </h4>
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
                    const ctx = document.getElementById('chart-{{ $monitoring->id }}').getContext('2d');
                    const labels = {!! json_encode(array_column($monitoring->data, 'date')) !!};
                    const data = {!! json_encode(array_column($monitoring->data, 'value')) !!};

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels.map(timestamp => new Date(timestamp *
                            1000)), // Konversi Unix timestamp menjadi objek Date
                            datasets: [{
                                label: '{{ $monitoring->parameter->name }}',
                                data: data,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        tooltipFormat: 'll HH:mm', // Format tooltip sesuaikan dengan kebutuhan Anda
                                        unit: 'day'
                                    },
                                    ticks: {
                                        source: 'data' // Gunakan 'data' untuk mengambil label dari data
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                @endif
            @endforeach
        });
    </script>
@endsection
