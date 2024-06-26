@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_analysis')) }}
@endsection

@section('analysis-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('analysis.index') }}">{{ ucwords(str_replace('_', ' ', 'analysis')) }}</a></li>
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
                        <form action="{{ route('analysis.update', $analysis->id) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="material_id">
                                    {{ ucwords(str_replace('_', ' ', 'material')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select id="material_id" name="material_id" required autofocus>
                                        <option disabled selected>Select a material :</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}"
                                                {{ $material->id == $analysis->material_id ? 'selected' : '' }}>
                                                @php echo ucwords(str_replace('_', ' ', $material->name)); @endphp
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="customer_id">
                                    {{ ucwords(str_replace('_', ' ', 'customer')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select id="customer_id" name="customer_id" required autofocus>
                                        <option disabled selected>Select a customer :</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $analysis->customer_id ? 'selected' : '' }}>
                                                @php echo ucwords(str_replace('_', ' ', $customer->name)); @endphp
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="batch">
                                    {{ ucwords(str_replace('_', ' ', 'batch')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="batch" name="batch" value="{{ $analysis->batch }}">
                                </div>
                            </div>

                            @foreach ($parameters as $parameter)
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label"
                                        for="{{ str_replace(' ', '_', $parameter->name) }}">
                                        {{ ucwords(str_replace('_', ' ', $parameter->name)) }}<sub>(@php echo $parameter->measurement_unit->name; @endphp)</sub>
                                    </label>
                                    <div class="col-sm-10">
                                        @if($parameter->type == "Numeric")
                                            <input type="number" step="any" class="form-control"
                                                id="{{ str_replace(' ', '_', $parameter->name) }}"
                                                name="{{ str_replace(' ', '_', $parameter->name) }}"
                                                value="{{ $analysis->{str_replace(' ', '_', $parameter->name)} }}"
                                                min="{{ $parameter->min }}" max="{{ $parameter->max }}"
                                            >
                                        @elseif($parameter->type == "Option")
                                            @foreach($parameter->parameter_option as $parameter_option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="{{ str_replace(' ', '_', $parameter->name) }}"
                                                        id="{{ str_replace(' ', '_', $parameter->name) }}"
                                                        value="{{ $parameter_option->option->name }}"
                                                        {{ ($analysis->{str_replace(' ', '_', $parameter->name)} == $parameter_option->option->name) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="type_numeric">{{ $parameter_option->option->name }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
        $('#material_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
        $('#customer_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
