@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'parameter_option')) }}
@endsection

@section('parameter_option-active')
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>List of <strong>@yield('title')</strong></h4>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="example" width="100%">
                        <thead>
                            <tr>
                                <th>{{ ucwords(str_replace('_', ' ', 'parameter')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'option')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parameters as $parameter)
                                <tr>
                                    <td>{{ $parameter->name }}</td>
                                    <td>
                                        @foreach ($parameter->parameter_option as $parameter_option)
                                            <li>{{ $parameter_option->option->name }}</li>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="btn-group" parameter="group" aria-label="manage">
                                            <a href="{{ route('parameter_option.adjust', $parameter->id) }}"
                                                class="btn btn-secondary btn-sm">Adjust</a>
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
@endsection
