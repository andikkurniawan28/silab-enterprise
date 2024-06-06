@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'adjust_parameter_option')) }}
@endsection

@section('parameter_option-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('parameter_option.index') }}">{{ ucwords(str_replace('_', ' ', 'parameter_option')) }}</a></li>
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
                        <form action="{{ route('parameter_option.update', $parameter->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="parameter">
                                    {{ ucwords(str_replace('_', ' ', 'parameter')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="parameter" name="parameter" value="{{ $parameter->name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label
                                    class="col-sm-2 col-form-label">{{ ucwords(str_replace('_', ' ', 'parameter_options')) }}</label>
                                <div class="col-sm-10">
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
            let checkboxes = document.querySelectorAll('.option-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
