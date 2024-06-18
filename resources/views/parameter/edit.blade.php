@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_parameter')) }}
@endsection

@section('parameter-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("parameter.index") }}">{{ ucwords(str_replace('_', ' ', 'parameter')) }}</a></li>
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
                        <form action="{{ route("parameter.update", $parameter->id) }}" method="POST">
                            @csrf @method("PUT")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="measurement_unit_id">
                                    {{ ucwords(str_replace('_', ' ', 'measurement_unit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select id="measurement_unit_id" name="measurement_unit_id" required autofocus>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'measurement_unit')) }} :</option>
                                        @foreach ($measurement_units as $measurement_unit)
                                            <option value="{{ $measurement_unit->id }}"
                                                {{ $measurement_unit->id == $parameter->measurement_unit_id ? 'selected' : '' }}>
                                                @php echo ucwords(str_replace('_', ' ', $measurement_unit->name)); @endphp
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- type --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">{{ ucwords(str_replace('_', ' ', 'type')) }}</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_numeric" value="Numeric" {{ $parameter->type == 'Numeric' ? 'checked' : '' }} onchange="determineType()" required autofocus>
                                        <label class="form-check-label" for="type_numeric">Numeric</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="type_option" value="Option" {{ $parameter->type == 'Option' ? 'checked' : '' }} onchange="determineType()">
                                        <label class="form-check-label" for="type_option">Option</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $parameter->name }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="min" id="label_min" style="display: none;">
                                    {{ ucwords(str_replace('_', ' ', 'min')) }}
                                </label>
                                <div class="col-sm-10" id="input_min" style="display: none;">
                                    <input type="number" step="any" class="form-control" id="min" name="min" value="{{ $parameter->min }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="max" id="label_max" style="display: none;">
                                    {{ ucwords(str_replace('_', ' ', 'max')) }}
                                </label>
                                <div class="col-sm-10" id="input_max" style="display: none;">
                                    <input type="number" step="any" class="form-control" id="max" name="max" value="{{ $parameter->max }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="behind_decimal" id="label_behind_decimal" style="display: none;">
                                    {{ ucwords(str_replace('_', ' ', 'behind_decimal')) }}
                                </label>
                                <div class="col-sm-10" id="input_behind_decimal" style="display: none;">
                                    <input type="number" step="any" class="form-control" id="behind_decimal" name="behind_decimal" value="{{ $parameter->behind_decimal }}">
                                </div>
                            </div>

                            {{-- option --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label option-section" style="display: none;">{{ ucwords(str_replace('_', ' ', 'options')) }}</label>
                                <div class="col-sm-10 option-section" style="display: none;">
                                    <!-- Checkbox Select All -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each option -->
                                    @foreach ($options as $option)
                                        @php
                                            $isChecked = $parameter_options->contains('option_id', $option->id);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input option-checkbox" type="checkbox"
                                                name="option_ids[]" value="{{ $option->id }}"
                                                id="option_{{ $option->id }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option_{{ $option->id }}">
                                                {{ $option->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- reporting_method --}}
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">{{ ucwords(str_replace('_', ' ', 'reporting_method')) }}</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="reporting_method" name="reporting_method" required>
                                        <option disabled selected>Select a {{ ucwords(str_replace('_', ' ', 'reporting_method')) }} :</option>
                                        <option value="{{ ucwords(str_replace('_', ' ', 'average')) }}" @if($parameter->reporting_method == ucwords(str_replace('_', ' ', 'average'))) {{ "selected" }} @endif>{{ ucwords(str_replace('_', ' ', 'average')) }}</option>
                                        <option value="{{ ucwords(str_replace('_', ' ', 'sum')) }}" @if($parameter->reporting_method == ucwords(str_replace('_', ' ', 'sum'))) {{ "selected" }} @endif>{{ ucwords(str_replace('_', ' ', 'sum')) }}</option>
                                        <option value="{{ ucwords(str_replace('_', ' ', 'count')) }}" @if($parameter->reporting_method == ucwords(str_replace('_', ' ', 'count'))) {{ "selected" }} @endif>{{ ucwords(str_replace('_', ' ', 'count')) }}</option>
                                        <option value="{{ ucwords(str_replace('_', ' ', 'qualitative')) }}" @if($parameter->reporting_method == ucwords(str_replace('_', ' ', 'qualitative'))) {{ "selected" }} @endif>{{ ucwords(str_replace('_', ' ', 'qualitative')) }}</option>
                                    </select>
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
            let checkboxes = document.querySelectorAll('.option-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });

        function determineType() {
            var typeNumeric = document.getElementById('type_numeric');
            var typeOption = document.getElementById('type_option');

            if (typeNumeric.checked) {
                showMinMax();
                hideOptions();
            } else if (typeOption.checked) {
                hideMinMax();
                showOptions();
            }
        }

        function showMinMax() {
            document.getElementById("label_min").style.display = "block";
            document.getElementById("input_min").style.display = "block";
            document.getElementById("label_max").style.display = "block";
            document.getElementById("input_max").style.display = "block";
            document.getElementById("label_behind_decimal").style.display = "block";
            document.getElementById("input_behind_decimal").style.display = "block";
        }

        function hideMinMax() {
            document.getElementById("label_min").style.display = "none";
            document.getElementById("input_min").style.display = "none";
            document.getElementById("label_max").style.display = "none";
            document.getElementById("input_max").style.display = "none";
            document.getElementById("label_behind_decimal").style.display = "none";
            document.getElementById("input_behind_decimal").style.display = "none";
        }

        function showOptions() {
            var optionSections = document.getElementsByClassName("option-section");
            for (var i = 0; i < optionSections.length; i++) {
                optionSections[i].style.display = "block";
            }
        }

        function hideOptions() {
            var optionSections = document.getElementsByClassName("option-section");
            for (var i = 0; i < optionSections.length; i++) {
                optionSections[i].style.display = "none";
            }
        }

        window.onload = function() {
            determineType();
        };

        $(document).ready(function() {
            $('#measurement_unit_id').select2({
                theme: 'bootstrap',
                width: '100%',
            });
        });

    </script>
@endsection
