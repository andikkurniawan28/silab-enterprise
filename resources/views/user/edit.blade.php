@extends('template.sneat.master')

@section('title')
    {{ ucwords(str_replace('_', ' ', 'edit_user')) }}
@endsection

@section('user-active')
    {{ 'active' }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route("user.index") }}">{{ ucwords(str_replace('_', ' ', 'user')) }}</a></li>
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
                        <form action="{{ route("user.update", $user->id) }}" method="POST">
                            @csrf @method("PUT")
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="role_id">
                                    {{ ucwords(str_replace('_', ' ', 'role')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="role_id" name="role_id" required autofocus>
                                        <option disabled selected>Select a role :</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">
                                    {{ ucwords(str_replace('_', ' ', 'name')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="username">
                                    {{ ucwords(str_replace('_', ' ', 'username')) }}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="is_active">
                                    {{ ucwords(str_replace('_', ' ', 'is_active')) }}
                                </label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="is_active" name="is_active" required autofocus>
                                        <option disabled selected>Select status:</option>
                                        <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>No</option>
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
