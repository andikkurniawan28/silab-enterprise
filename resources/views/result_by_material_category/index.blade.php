@extends('template.sneat.master')

@section('title', 'Results by Station')

@section('result_by_material_category-active')
    {{ 'active' }}
@endsection

@section("result_by_material_category_id_{$material_category_selected->id}-active")
    {{ 'active' }}
@endsection

@section('content')
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>Results of <strong>{{ ucwords(str_replace('_', ' ', $material_category_selected->name)) }}</strong></h4>

                <!-- Form with material_category dropdown and date & time inputs -->
                <form id="filterForm" action="{{ route('change_datetime') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="from_datetime" class="form-label">From</label>
                            <input type="datetime-local" class="form-control" id="from_datetime" name="from_datetime"
                                value="{{ session('from_datetime') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="to_datetime" class="form-label">To</label>
                            <input type="datetime-local" class="form-control" id="to_datetime" name="to_datetime"
                                value="{{ session('to_datetime') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" id="filterButton">Filter</button>
                </form>

                {{-- Tampilkan kartu di sini --}}
                <div class="row mt-4">
                    @foreach ($material_category_selected->material as $material)
                        <div class="col-lg-6 col-md-12">
                            <div class="card mb-4 shadow">
                                <div class="card-header">
                                    <h5 class="card-title"><a href="{{ route('result_by_material.index', $material->id) }}"
                                            target="_blank">{{ ucwords(str_replace('_', ' ', $material->name)) }}</a>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>{{ ucwords(str_replace('_', ' ', 'time')) }}</th>
                                                    @foreach ($material->material_parameter as $material_parameter)
                                                        <th>{{ ucwords(str_replace('_', ' ', $material_parameter->parameter->name)) }}<sub>({{ $material_parameter->parameter->measurement_unit->name }})</sub>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($material->analysis as $analysis)
                                                    <tr>
                                                        <td>{{ date("H:i", strtotime($analysis->created_at)) }}</td>
                                                        @foreach ($material->material_parameter as $material_parameter)
                                                            <td>{{ !is_null($analysis->{$material_parameter->parameter->name}) ? number_format($analysis->{$material_parameter->parameter->name}, 2) : '' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional_script')
    <!-- Tidak ada JavaScript tambahan -->
@endsection