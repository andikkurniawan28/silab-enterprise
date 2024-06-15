@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_report_type')) }}
@endsection

@section('report_type-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("report_type.index") }}">{{ ucwords(str_replace('_', ' ', 'report_type')) }}</a></li>
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
                        <form action="{{ route("report_type.store") }}" method="POST">
                            @csrf @method("POST")

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label material-section">{{ ucwords(str_replace('_', ' ', 'materials')) }}</label>
                                <div class="col-sm-10 material-section">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all">
                                        <label class="form-check-label" for="select_all">
                                            Select All
                                        </label>
                                    </div>
                                    <hr>
                                    <!-- Checkbox for each material -->
                                    @foreach ($materials as $material)
                                        <div class="form-check">
                                            <input class="form-check-input material-checkbox" type="checkbox"
                                                name="material_ids[]" value="{{ $material->id }}"
                                                id="material_{{ $material->id }}">
                                            <label class="form-check-label" for="material_{{ $material->id }}">
                                                {{ $material->name }}
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
            let checkboxes = document.querySelectorAll('.material-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
