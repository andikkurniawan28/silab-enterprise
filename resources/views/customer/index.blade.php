@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'customer')) }}
@endsection

@section('customer-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('customer.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-bordered table-hovered" id="customer_table" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'company')) }}</th>
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
            var table = $('#customer_table').DataTable({
                layout: {
                    bottomStart: {
                        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                    },
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'company_id', name: 'company.name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                initComplete: function(settings, json) {
                    var api = this.api();
                    var headers = api.columns().header();
                    // Optional: Custom header processing if needed
                }
            });

            // Delete button handling
            $('#customer_table').on('click', '.delete-btn', function() {
                var customer_id = $(this).data('id');
                var customer_name = $(this).data('name');
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
                        var form = document.createElement('form');
                        form.setAttribute('method', 'POST');
                        form.setAttribute('action', `{{ route('customer.destroy', ':id') }}`.replace(':id', customer_id));
                        var csrfToken = document.getElementsByName("_token")[0].value;

                        var hiddenMethod = document.createElement('input');
                        hiddenMethod.setAttribute('type', 'hidden');
                        hiddenMethod.setAttribute('name', '_method');
                        hiddenMethod.setAttribute('value', 'DELETE');

                        var name = document.createElement('input');
                        name.setAttribute('type', 'hidden');
                        name.setAttribute('name', 'name');
                        name.setAttribute('value', customer_name);

                        var csrfTokenInput = document.createElement('input');
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
            });
        });
    </script>
@endsection
