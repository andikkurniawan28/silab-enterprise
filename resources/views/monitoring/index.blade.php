@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'monitoring')) }}
@endsection

@section('monitoring-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('monitoring.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="monitoring_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'id')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'timestamp')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'material')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'parameter')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'method')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
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
            var table = $('#monitoring_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('monitoring.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'material_id', name: 'material.name' },
                    { data: 'parameter_id', name: 'parameter.name' },
                    { data: 'method', name: 'method' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                initComplete: function(settings, json) {
                    var api = this.api();
                    var headers = api.columns().header();
                    // Optional: Custom header processing if needed
                }
            });
        });

        // Delete button handling
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi DataTable
            const table = $('#example').DataTable();
            console.log('DataTable initialized');

            // Delegasi event untuk tombol delete
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-btn')) {
                    event.preventDefault();
                    console.log('Delete button clicked');
                    const button = event.target;
                    const monitoring_id = button.getAttribute('data-id');
                    const monitoring_name = button.getAttribute('data-name');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You won\'t be able to revert this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action',
                                `{{ route('monitoring.destroy', ':id') }}`.replace(
                                    ':id', monitoring_id));
                            const csrfToken = document.getElementsByName("_token")[0]
                                .value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', monitoring_name);

                            const csrfTokenInput = document.createElement('input');
                            csrfTokenInput.setAttribute('type', 'hidden');
                            csrfTokenInput.setAttribute('name', '_token');
                            csrfTokenInput.setAttribute('value', csrfToken);

                            form.appendChild(hiddenMethod);
                            form.appendChild(name);
                            form.appendChild(csrfTokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
