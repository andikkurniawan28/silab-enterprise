@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'parameter')) }}
@endsection

@section('parameter-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="btn-group" role="group" aria-label="manage">
                    <a href="{{ route('parameter.create') }}" class="btn btn-sm btn-primary">Create</a>
                </div>
                <div class="table-responsive">
                    <span class="half-line-break"></span>
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'name')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'type')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'description')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parameters as $parameter)
                                <tr>
                                    <td>{{ $parameter->name }}<sub>(@php echo $parameter->measurement_unit->name; @endphp)</sub></td>
                                    <td>{{ $parameter->type }}</td>
                                    <td>
                                        @if($parameter->type == "Numeric")
                                            <li>Min : {{ $parameter->min }}</li>
                                            <li>Max : {{ $parameter->max }}</li>
                                            <li>Behind Decimal : {{ $parameter->behind_decimal }}</li>
                                            <li>Reporting Method : {{ $parameter->reporting_method }}</li>
                                        @elseif($parameter->type == "Option")
                                            @foreach($parameter->parameter_option as $parameter_option)
                                            <li>Option : {{ $parameter_option->option->name }}</li>
                                            @endforeach
                                            <li>Reporting Method : {{ $parameter->reporting_method }}</li>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('parameter.edit', $parameter->id) }}"
                                                class="btn btn-secondary btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $parameter->id }}"
                                                data-name="{{ $parameter->name }}">Delete</button>
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
                    const parameter_id = this.getAttribute('data-id');
                    const parameter_name = this.getAttribute('data-name');
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
                                `{{ route('parameter.destroy', ':id') }}`.replace(
                                    ':id', parameter_id));
                            const csrfToken = document.getElementsByName("_token")[0].value;

                            const hiddenMethod = document.createElement('input');
                            hiddenMethod.setAttribute('type', 'hidden');
                            hiddenMethod.setAttribute('name', '_method');
                            hiddenMethod.setAttribute('value', 'DELETE');

                            const name = document.createElement('input');
                            name.setAttribute('type', 'hidden');
                            name.setAttribute('name', 'name');
                            name.setAttribute('value', parameter_name);

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
