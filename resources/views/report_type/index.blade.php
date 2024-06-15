@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'report_type')) }}
@endsection

@section('report_type-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('report_type.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report_types as $report_type)
                                <tr>
                                    <td>
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePermissions{{ $report_type->id }}" aria-expanded="false" aria-controls="collapsePermissions{{ $report_type->id }}">
                                            <strong>{{ $report_type->name }}</strong>
                                        </button>
                                        <div class="collapse" id="collapsePermissions{{ $report_type->id }}">
                                            <ul>
                                                @foreach($report_type->report_type_content as $report_type_content)
                                                    <li>{{ $report_type_content->material->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('report_type.edit', $report_type->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $report_type->id }}" data-name="{{ $report_type->name }}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const report_type_id = this.getAttribute('data-id');
                    const report_type_name = this.getAttribute('data-name');
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
                                `{{ route('report_type.destroy', ':id') }}`.replace(
                                    ':id', report_type_id));
                            const csrfToken = document.getElementsByName("_token")[0].value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', report_type_name);

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
                });
            });
        });
    </script>
@endsection
