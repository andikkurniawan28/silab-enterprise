@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'material')) }}
@endsection

@section('material-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('material.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'station')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'material_category')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <td>
                                        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePermissions{{ $material->id }}" aria-expanded="false" aria-controls="collapsePermissions{{ $material->id }}">
                                            <strong>{{ $material->name }}</strong>
                                        </button>
                                        <div class="collapse" id="collapsePermissions{{ $material->id }}">
                                            <ul>
                                                @foreach($material->material_parameter as $material_parameter)
                                                    <li>{{ $material_parameter->parameter->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $material->station->name }}</td>
                                    <td>{{ $material->material_category->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('material.edit', $material->id) }}"
                                                class="btn btn-secondary btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $material->id }}"
                                                data-name="{{ $material->name }}">Delete</button>
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
            // Inisialisasi DataTable
            const table = $('#example').DataTable();
            console.log('DataTable initialized');

            // Delegasi event untuk tombol delete
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-btn')) {
                    event.preventDefault();
                    console.log('Delete button clicked');
                    const button = event.target;
                    const material_id = button.getAttribute('data-id');
                    const material_name = button.getAttribute('data-name');
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
                                `{{ route('material.destroy', ':id') }}`.replace(
                                    ':id', material_id));
                            const csrfToken = document.getElementsByName("_token")[0].value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', material_name);

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
