@extends('layouts/main')


@section('styles')
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.user.userManagement') }}</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('user') }}">{{ __('translation.menu.user') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.create') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.formTitle.userCreate') }}</h3>
        </div>
        <div class="card-body">
            <form id="user_create" class="jquery-validate-form" method="POST" action="{{ url('user') }}"
                autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="form-group">
                                <label for="username">{{ __('translation.user.userName') }}<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="username" id="username">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="first_name">{{ __('translation.user.firstName') }}<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="first_name" id="first_name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">{{ __('translation.user.lastName') }}<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="last_name" id="last_name">
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('translation.user.email') }}<span class="text-danger">
                                        *</span></label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">{{ __('translation.user.password') }}<span class="text-danger">
                                        *</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    autocomplete="new-password">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role_id">{{ __('translation.user.role') }}<span class="text-danger">
                                        *</span></label>
                                <select class="form-control select2-show-search form-select" name="role" id="role"
                                    data-placeholder="{{ __('translation.placeHolder.selectRole') }}"
                                    {{ Auth::user()->role == UserRole::ADMIN ? 'disabled' : '' }}>
                                    <option label="{{ __('translation.placeHolder.selectRole') }}" disabled selected>
                                    </option>
                                    @foreach ($roles as $role)
                                        <option
                                            {{ Auth::user()->role == UserRole::ADMIN && $role == UserRole::USER ? 'selected' : '' }}
                                            value="{{ $role }}">{{ __('translation.user.' . $role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12"
                        style="display: {{ Auth::user()->role == UserRole::ADMIN ? 'block' : 'none' }};"
                        id="rolePermission">
                        <div>
                            <div class="mb-3">
                                <b class="text-uppercase">{{ __('translation.role.rolePermission') }}</b>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-check ps-0 pe-0">
                                        <label class="ckbox mb-0" for="check-all">
                                            <input type="checkbox" id="check-all">
                                            <span>{{ __('translation.button.all') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <?php foreach ($permissions as $permission) : ?>
                                <div class="col-sm-6">
                                    <div class="form-check mb-3 ps-0 pe-0">
                                        <label class="ckbox" for="permission-{{ $permission['id'] }}">
                                            <input type="checkbox" name="permission_ids[]"
                                                id="permission-{{ $permission['id'] }}" value="{{ $permission['id'] }}">
                                            <span>{{ __('translation.permission.' . $permission['code']) }}</span>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger me-4" type="button"
                            onclick="back()">{{ __('translation.button.cancel') }}</button>
                        <button class="btn btn-primary" type="submit">{{ __('translation.button.create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()

@section('scripts')
    <script src="{{ asset('assets/js/page/user/user.create.js') }}"></script>
@endSection()
