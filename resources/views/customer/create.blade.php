@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_monitoring')) }}
@endsection

@section('monitoring-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('monitoring.index') }}">{{ ucwords(str_replace('_', ' ', 'monitoring')) }}</a></li>
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
                        <form action="{{ route('monitoring.store') }}" method="POST">
                            @csrf @method('POST')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material_id">
                                    {{ ucwords(str_replace('_', ' ', 'material')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="material_id" id="material_id" name="material_id" required autofocus>
                                        <option disabled selected>Select a material :</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">@php echo ucwords(str_replace('_', ' ', $material->name)); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="parameter_id">
                                    {{ ucwords(str_replace('_', ' ', 'parameter')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="parameter_id" id="parameter_id" name="parameter_id" required>
                                        <option disabled selected>Select a parameter :</option>
                                        @foreach ($parameters as $parameter)
                                            <option value="{{ $parameter->id }}">@php echo ucwords(str_replace('_', ' ', $parameter->name)); @endphp</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="method">
                                    {{ ucwords(str_replace('_', ' ', 'method')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="method" id="method" name="method" required>
                                        <option disabled selected>Select a method :</option>
                                        <option>Latest</option>
                                        <option>Average</option>
                                        <option>Minimum</option>
                                        <option>Maximum</option>
                                        <option>Summary</option>
                                        <option>Count</option>
                                        <option>Trendline</option>
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
    $(document).ready(function() {
        $('.material_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('.parameter_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('.method').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
