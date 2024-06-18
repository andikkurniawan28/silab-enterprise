@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'create_customer')) }}
@endsection

@section('customer-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('customer.index') }}">{{ ucwords(str_replace('_', ' ', 'customer')) }}</a></li>
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
                        <form action="{{ route('customer.store') }}" method="POST">
                            @csrf @method('POST')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="company_id">
                                    {{ ucwords(str_replace('_', ' ', 'company')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="company_id" id="company_id" name="company_id" required>
                                        <option disabled selected>Select a company :</option>
                                        @foreach ($companys as $company)
                                            <option value="{{ $company->id }}">@php echo ucwords(str_replace('_', ' ', $company->name)); @endphp</option>
                                        @endforeach
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
        $('.company_id').select2({
            theme: 'bootstrap',
            width: '100%',
        });
    });
</script>
@endsection
