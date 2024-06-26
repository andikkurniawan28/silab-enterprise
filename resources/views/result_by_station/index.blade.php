@extends('template.sneat.master')

@section('result_by_station-active')
    {{ 'active' }}
@endsection

@section("result_by_station_id_{$station_selected->id}-active")
    {{ 'active' }}
@endsection

@section('content')
    @php
        $permissions = collect($setup->permission)
            ->pluck('feature.route')
            ->toArray();
    @endphp
    @csrf
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-body">
                <h4>Results of <strong>{{ ucwords(str_replace('_', ' ', $station_selected->name)) }}</strong></h4>

                {{-- Tampilkan kartu di sini --}}
                <div class="row mt-4">
                    @foreach ($station_selected->material as $material)
                        <div class="col-lg-6 col-md-12">
                            <div class="card mb-4 shadow">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        @if (in_array('result_by_material.index', $permissions))
                                            <a href="{{ route('result_by_material.index', $material->id) }}"
                                                target="_blank">{{ ucwords(str_replace('_', ' ', $material->name)) }}</a>
                                        @else
                                            {{ ucwords(str_replace('_', ' ', $material->name)) }}
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>{{ ucwords(str_replace('_', ' ', 'time')) }}</th>
                                                    <th>{{ ucwords(str_replace('_', ' ', 'batch')) }}</th>
                                                    @foreach ($material->material_parameter as $material_parameter)
                                                        <th>{{ ucwords(str_replace('_', ' ', $material_parameter->parameter->name)) }}<sub>({{ $material_parameter->parameter->measurement_unit->name }})</sub>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($material->analysis as $analysis)
                                                    <tr>
                                                        <td>{{ date('H:i', strtotime($analysis->created_at)) }}</td>
                                                        <td>{{ $analysis->batch }}</td>
                                                        @foreach ($material->material_parameter as $material_parameter)
                                                            <td>
                                                                @if($material_parameter->parameter->type == "Numeric")
                                                                    {{ !is_null($analysis->{ucwords(str_replace(' ', '_', $material_parameter->parameter->name))}) ? number_format($analysis->{ucwords(str_replace(' ', '_', $material_parameter->parameter->name))}, $material_parameter->parameter->behind_decimal) : '' }}
                                                                @elseif($material_parameter->parameter->type == "Option")
                                                                    {{ $analysis->{ucwords(str_replace(' ', '_', $material_parameter->parameter->name))} }}
                                                                @endif
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
