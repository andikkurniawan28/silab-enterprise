@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_parameter')) }}
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
                        <form action="{{ route("parameter.store") }}" method="POST">
                            @csrf @method("POST")
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="measurement_unit_id">
                                    {{ ucwords(str_replace('_', ' ', 'measurement_unit')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="measurement_unit_id" name="measurement_unit_id" required autofocus>
                                        <option disabled selected>Select a measurement unit :</option>
                                        @foreach ($measurement_units as $measurement_unit)
                                            <option value="{{ $measurement_unit->id }}">@php echo ucwords(str_replace('_', ' ', $measurement_unit->name)); @endphp</option>
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
                                <label class="col-sm-2 col-form-label" for="min">
                                    {{ ucwords(str_replace('_', ' ', 'min')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" class="form-control" id="min" name="min" value="{{ old("min") }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="max">
                                    {{ ucwords(str_replace('_', ' ', 'max')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="number" step="any" class="form-control" id="max" name="max" value="{{ old("max") }}" required>
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
