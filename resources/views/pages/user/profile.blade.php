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
            bottom: 20%;
            cursor: pointer;
            right: 0;
        }

        #profile-img-file-input {
            width: 0;
            height: 0;
        }

        #imagePreview {
            width: 100px;
            height: 100px;
            overflow: hidden;
        }

        #imagePreview {
            cursor: pointer;
        }
    </style>
@endsection()

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('translation.menu.profile') }}</h1>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 OPEN -->
    <div class="row" id="user-profile">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 col-xl-6">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="profile-img-main rounded">
                                    <img src="@if ($profile->profile_picture) {{ $profile->profile_picture }}@else{{ URL::asset('assets/images/faces/default-avatar.png') }} @endif"
                                        id="imagePreview" alt="img" class="m-0 p-1 rounded border">
                                    <label for="profile-img-file-input" class="profile-photo-edit">
                                        <i class="ri-camera-fill"></i>
                                    </label>
                                </div>
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                    name="avatar" accept="image/*">
                                <div class="ms-4">
                                    <h4>{{ $profile->first_name . ' ' . $profile->last_name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xl-6">
                            <!-- EMPTY -->
                        </div>
                    </div>
                </div>
                <div class="border-top">
                    <div class="wideget-user-tab">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu1">
                                <ul class="nav">
                                    <li><a href="#editProfile" class="active show"
                                            data-bs-toggle="tab">{{ __('translation.user.editProfile') }}</a></li>
                                    <li><a href="#changePassword"
                                            data-bs-toggle="tab">{{ __('translation.user.changePassword') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="editProfile">
                    <div class="card">
                        <div class="card-body border-0">
                            <form class="form-horizontal jquery-validate-form" id="info_update" method="POST"
                                action="{{ url('profile') }}">
                                @csrf
                                <input type="text" class="form-control" id="userId" name="userId"
                                    value="{{ $profile->id }}" hidden>
                                <div class="row mb-4">
                                    <p class="mb-4 text-17">{{ __('translation.user.personalInfo') }}</p>
                                    <div class="col-md-12 col-xl-6">
                                        <div class="form-group">
                                            <label for="first_name"
                                                class="form-label">{{ __('translation.user.firstName') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="{{ $profile->first_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-6">
                                        <div class="form-group">
                                            <label for="last_name"
                                                class="form-label">{{ __('translation.user.lastName') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{ $profile->last_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-6">
                                        <div class="form-group">
                                            <label for="email"
                                                class="form-label">{{ __('translation.user.email') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{ $profile->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group float-start">
                                    <button class="btn btn-primary"
                                        type="submit">{{ __('translation.button.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="changePassword">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal jquery-validate-form" id="password_update" method="POST"
                                action="{{ 'profile/password' }}">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="password"
                                                class="form-label">{{ __('translation.user.password') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password"
                                                class="form-label">{{ __('translation.user.newPassword') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password"
                                                class="form-label">{{ __('translation.user.confirmPassword') }}<span
                                                    class="text-danger"> *</span></label>
                                            <input type="password" class="form-control" id="new_confirm_password"
                                                name="new_confirm_password">
                                        </div>
                                        <div class="form-group float-start">
                                            <button class="btn btn-primary"
                                                type="submit">{{ __('translation.button.submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- ROW-1 CLOSED -->
@endSection()

@section('scripts')
    <script>
        const userId = {{ $profile->id }};

        $('#profile-img-file-input').on('change', function() {
            const file = this.files[0];
            if (!file) {
                return;
            }
            if (!file.type.startsWith('image/') || !/\.(jpeg|png|jpg|gif|svg)$/i.test(file.name)) {
                notification("error", trans("validation.importPhotos", {
                    field: 'jpeg, png, jpg, gif, svg'
                }));
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                notification('error', trans("validation.largerThanFile", {
                    field: 5
                }));
                return;
            }
            const reader = new FileReader();

            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(file);
            uploadImage(file);
        });

        function uploadImage(file) {
            const apiUrl = 'api/v1/user/avatar';
            let formData = new FormData();
            formData.append('avatar', file);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: apiUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile_picture').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                    notification("success", trans("message.updateSuccess"));
                },
                error: function(error) {
                    notification("error", trans("message.updateFailed"));
                }
            });
        }

        $('#imagePreview').on('click', function() {
            $('#profile-img-file-input').click();
        });
    </script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/user/profile.edit.js') }}"></script>
@endSection()
