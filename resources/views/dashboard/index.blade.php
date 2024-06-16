@extends('template.sneat.master')

@section('dashboard-active')
    {{ 'active' }}
@endsection

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4>{{ ucwords(str_replace(" ", "_", "dashboard")) }}</h4>

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
                <div class="col-lg-3 mb-4 order-0">
                    <div class="card bg-primary">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-12">
                                <div class="card-body text-center">
                                    <h4 class="card-title text-white">
                                        {{ $monitoring->parameter->name }}
                                        {{ $monitoring->material->name }}
                                        {{ $monitoring->method }}
                                        </h5>
                                        <br>
                                        <h2 class="mb-4 text-white">
                                            @if ($monitoring->data != null)
                                                {{ number_format($monitoring->data, $monitoring->parameter->behind_decimal) }}
                                                @php echo $monitoring->parameter->measurement_unit->name; @endphp
                                            @else
                                                -
                                            @endif
                                            </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    <!-- / Content -->
@endsection
