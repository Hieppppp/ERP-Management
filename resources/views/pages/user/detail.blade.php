@extends('layouts/main')


@section('styles')
    <style>
        .profile-img-main {
            position: relative;
        }

        .profile-photo-edit {
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            background-color: #fff;
            border-radius: 50%;
            margin-bottom: 0 !important;
            bottom: 0%;
            right: 0;
        }

        #profile-img-file-input {
            width: 0;
            height: 0;
        }

        #imagePreview {
            overflow: hidden;
        }

        .iti {
            display: block !important;
        }

        #profile-img-file-input-error {
            display: flex;
            justify-content: center;
        }
    </style>
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
                <li class="breadcrumb-item active" aria-current="page">{{ __('translation.detail') }}</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW -->
    <div class="card">
        <div class="card-header border-bottom">
            <h3 class="card-title">{{ __('translation.user.detail') }}</h3>
        </div>
        <div class="card-body">
            <form id="user_edit" class="jquery-validate-form" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="userId" value="<?= $user->id ?>">
                        <div class="row">
                            <div class="form-group">
                                <label for="username">{{ __('translation.user.userName') }}</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    value="<?= $user->username ?>" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="first_name">{{ __('translation.user.firstName') }}</label>
                                <input type="text" class="form-control" name="first_name" id="first_name"
                                    value="<?= $user->first_name ?>" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">{{ __('translation.user.lastName') }}</label>
                                <input type="text" class="form-control" name="last_name" id="last_name"
                                    value="<?= $user->last_name ?>" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">{{ __('translation.user.email') }}</label>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="<?= $user->email ?>" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role_id">{{ __('translation.user.role') }}</label>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="{{ $user->role == 'user' ? __('translation.user.user') : __('translation.user.admin') }}"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name">{{ __('translation.user.userImage') }}</label>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-md-12">
                                    <div class="d-flex flex-wrap align-items-center justify-content-center">
                                        <div class="profile-img-main">
                                            <img src="{{ $user->profile_picture ?? URL::asset('assets/images/add-image.png') }}"
                                                id="imagePreview" alt="img"
                                                class="m-0 p-1 border select-image img-overlay-light-box">
                                        </div>
                                        <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                            name="logo" accept="image/*" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            @include('pages.user.log', ['logs' => $activities])
        </div>
    </div>
    <!-- ROW CLOSED -->
@endSection()
