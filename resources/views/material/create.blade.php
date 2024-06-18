@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_material')) }}
@endsection

@section('material-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("material.index") }}">{{ ucwords(str_replace('_', ' ', 'material')) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield("title")</li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@yield('title')</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("material.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="station_id">
                                    {{ ucwords(str_replace('_', ' ', 'station')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="station_id" id="station_id" name="station_id" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'station')) }} :</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station->id }}">@php echo ucwords(str_replace('_', ' ', $station->name)); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material_category_id">
                                    {{ ucwords(str_replace('_', ' ', 'material_category')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="material_category_id" id="material_category_id" name="material_category_id" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'material_category')) }} :</option>
                                        @foreach ($material_categories as $material_category)
                                            <option value="{{ $material_category->id }}">@php echo ucwords(str_replace('_', ' ', $material_category->name)); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label parameter-section">{{ ucwords(str_replace('_', ' ', 'parameters')) }}</label>
                                <div class="col-sm-10 parameter-section">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each parameter -->
                                    @foreach ($parameters as $parameter)
                                        <div class="form-check">
                                            <input class="form-check-input parameter-checkbox" type="checkbox"
                                                name="parameter_ids[]" value="{{ $parameter->id }}"
                                                id="parameter_{{ $parameter->id }}">
                                            <label class="form-check-label" for="parameter_{{ $parameter->id }}">
                                                {{ $parameter->name }}
                                                <sub>({{ $parameter->measurement_unit->name }})</sub>
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
@endsection

@section('additional_script')
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.parameter-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });

        $(document).ready(function() {
            $('.station_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });

            $('.material_category_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });
    </script>
@endsection
