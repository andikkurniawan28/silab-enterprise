@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', $material->name)) }}
@endsection

@section('result_by_station-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>Result of <strong>@yield('title')</strong></h4>

                <!-- Form with date & time inputs -->
                <form id="datetimeForm" action="{{ route('change_datetime') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="from_datetime" class="form-label">From</label>
                            <input type="datetime-local" class="form-control" id="from_datetime" name="from_datetime" value="{{ session("from_datetime") }}">
                        </div>
                        <div class="col-md-6">
                            <label for="to_datetime" class="form-label">To</label>
                            <input type="datetime-local" class="form-control" id="to_datetime" name="to_datetime" value="{{ session("to_datetime") }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" id="filterButton">Filter</button>
                </form>

                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="results_by_material_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'timestamp')) }}</th>
                                @foreach ($material_parameters as $material_parameter)
                                    <th>{{ ucwords(str_replace('_', ' ', $material_parameter->parameter->name)) }}<sub>({{ $material_parameter->parameter->measurement_unit->name }})</sub></th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#results_by_material_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('result_by_material.index', $material->id) }}",
                    data: function(d) {
                        d.from_datetime = $('#from_datetime').val();
                        d.to_datetime = $('#to_datetime').val();
                    }
                },
                order: [
                    [0, 'desc']
                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        searchable: true,
                    },
                    @foreach ($material_parameters as $material_parameter)
                        {
                            data: '{{ str_replace(' ', '_', $material_parameter->parameter->name) }}',
                            name: '{{ str_replace(' ', '_', $material_parameter->parameter->name) }}',
                            orderable: true,
                            searchable: true,
                        },
                    @endforeach
                ]
            });

            $('#datetimeForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        });
                    }
                });
            });
        });
    </script>
@endsection
