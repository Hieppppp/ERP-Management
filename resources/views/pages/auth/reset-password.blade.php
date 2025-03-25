@extends('layouts.custom-main')
@section('styles')
@endsection

@section('body')

    <body class="ltr login-img">
    @endsection()

    @section('content')
        <div class="col mx-auto text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="">
            </a>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body" style="max-width:400px;">
                    <form method="post" action="{{ url('reset-password') }}"
                        class="login100-form validate-form jquery-validate-form" id="resetPasswordForm">
                        @csrf
                        <span class="login100-form-title">
                            <?php echo __('translation.auth.resetPassword'); ?>
                        </span>
                        <?php if (session()->exists('errors') && is_array(session('errors'))) {
                    ?>
                        <div class="alert alert-danger">{{ session('errors') }}</div>
                        <?php } ?>
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <div class="wrap-input100 validate-input" data-bs-validate="Valid <?php echo __('email'); ?> is required">
                            <input class="input100" type="text" placeholder="<?php echo __('email'); ?>" value="<?= $email ?>"
                                required disabled>
                            <input type="hidden" name="email" value="<?= $email ?>">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>

                        </div>
                        <?php if (!empty(session('email'))) { ?>
                        <div class="invalid-feedback" style="display: block !important;"><?= session('email') ?></div>
                        <?php } ?>
                        <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                            <input class="input100" type="password" name="password" id="password" placeholder="Password"
                                required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>

                        </div>
                        <?php if (!empty(session('password'))) { ?>
                        <div class="invalid-feedback" style="display: block !important;"><?= session('password') ?></div>
                        <?php } ?>
                        <div class="wrap-input100 validate-input" data-bs-validate="Password Confirmation">
                            <input class="input100" type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Password Confirmation" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        <?php if (!empty(session('password_confirmation'))) { ?>
                        <div class="invalid-feedback" style="display: block !important;">
                            <?= session('password_confirmation') ?></div>
                        <?php } ?>
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                <?php echo __('translation.auth.submit'); ?>
                            </button>
                        </div>
                        <div class="text-center pt-3">
                            <p class="text-dark mb-0"><?= __('translation.auth.alreadyHaveAccount') ?>?<a
                                    href="{{ url('login') }}"
                                    class="text-primary ms-1"><?= __('translation.auth.signIn') ?></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection()

    @section('scripts')
        <script>
            $("#resetPasswordForm").validate({
                rules: {
                    password: {
                        minlength: 8,
                        passwordRegex: true,
                    },
                    password_confirmation: {
                        equalTo: '#password'
                    },
                },
                messages: {
                    password_confirmation: {
                        equalTo: trans("validation.equalTo", {
                            field: trans('translation.user.newPassword')
                        }),
                    },
                },
                errorPlacement: function(error, elements) {
                    error.insertAfter(elements.closest('.validate-input'));
                },
            });
        </script>
    @endsection()
