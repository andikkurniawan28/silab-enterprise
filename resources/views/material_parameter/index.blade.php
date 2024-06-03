@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'material_parameter')) }}
@endsection

@section('material_parameter-active')
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
                                <th>{{ ucwords(str_replace('_', ' ', 'material')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'parameter')) }}</th>
                                <th>{{ ucwords(str_replace('_', ' ', 'manage')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <td>{{ $material->name }}</td>
                                    <td>
                                        @foreach ($material->material_parameter as $material_parameter)
                                            <li>{{ $material_parameter->parameter->name }}<sub>(@php echo $material_parameter->parameter->measurement_unit->name; @endphp)</sub></li>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="manage">
                                            <a href="{{ route('material_parameter.adjust', $material->id) }}"
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
