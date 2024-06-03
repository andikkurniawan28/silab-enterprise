@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'adjust_material_parameter')) }}
@endsection

@section('material_parameter-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('material_parameter.index') }}">{{ ucwords(str_replace('_', ' ', 'material_parameter')) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('material_parameter.update', $material->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material">
                                    {{ ucwords(str_replace('_', ' ', 'material')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="material" name="material" value="{{ $material->name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label
                                    class="col-sm-2 col-form-label">{{ ucwords(str_replace('_', ' ', 'material_parameters')) }}</label>
                                <div class="col-sm-10">
                                    <!-- Checkbox Select All -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each parameter -->
                                    @foreach ($parameters as $parameter)
                                        @php
                                            $isChecked = $material_parameters->contains('parameter_id', $parameter->id);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input parameter-checkbox" type="checkbox"
                                                name="parameter_ids[]" value="{{ $parameter->id }}"
                                                id="parameter_{{ $parameter->id }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="parameter_{{ $parameter->id }}">
                                                {{ $parameter->name }}<sub>(@php echo $parameter->measurement_unit->name; @endphp)</sub>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Select All functionality -->
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.parameter-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
