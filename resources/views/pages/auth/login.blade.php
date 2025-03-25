@extends('layouts.custom-main')
@section('styles')
@endsection

@section('body')

    <body class="ltr login-img">
    @endsection()

    @section('content')
        <div class="col col-login mx-auto text-center">
            <a href="<?php echo url('index'); ?>" class="text-center">
                <img src="<?php echo url('assets/images/brand/logo.png'); ?>" class="header-brand-img" alt="">
            </a>
        </div>
        <div class="container-login100">
            <div class="wrap-login100 p-0">
                <div class="card-body" style="max-width:400px;">
                    <form class="login100-form validate-form jquery-validate-form" method="POST" action=""
                        href="<?php echo url('/login'); ?>" id='loginForm'>
                        @csrf
                        <span class="login100-form-title">
                            <?php echo __('translation.auth.login'); ?>
                        </span>
                        @if (session()->exists('error'))
                            <div class="alert alert-danger"> <?= __('message.auth.usernamePasswordIncorrect') ?></div>
                        @endif
                        <div class="wrap-input100 validate-input" data-bs-validate="Valid <?php echo __('translation.auth.username'); ?> is required">
                            <input class="form-control input100" type="text" name="username"
                                placeholder="<?php echo __('translation.auth.username'); ?>" value="<?= old('username') ?>" required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                            <input class=" form-control input100" type="password" name="password" placeholder="Password"
                                required>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="text-end pt-1">
                            <p class="mb-0"><a href="<?php echo url('forgot-password'); ?>"
                                    class="text-primary ms-1"><?= __('translation.auth.forgotPassword') ?> ?</a></p>
                        </div>
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                <?php echo __('translation.auth.login'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection()

    @section('scripts')
        <script>
            $("#loginForm").validate({
                rules: {
                    password: {
                        required: true
                    },
                },
                errorPlacement: function(error, elements) {
                    error.insertAfter(elements.closest('.validate-input'));
                },
            });
        </script>
    @endsection()
