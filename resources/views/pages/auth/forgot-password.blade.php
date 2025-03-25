@extends('layouts.custom-main')
@section('styles')
@endsection

@section('body')

    <body class="ltr login-img">
    @endsection()

    @section('content')
        <!-- CONTAINER OPEN -->
        <div class="col mx-auto text-center">
            <a href="<?php echo url('index'); ?>">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="">
            </a>
        </div>
        <div class="col-12 container-login100">
            <div class="row">
                <div class="col col-login mx-auto">
                    <form class="card shadow-none jquery-validate-form" method="post"
                        action="<?= url('/forgot-password') ?>" id='forgotPasswordForm'>
                        @csrf
                        <div class="card-body" style="max-width:400px;">
                            <div class="text-center">
                                <span class="login100-form-title">
                                    <?= trans('translation.auth.forgotPassword') ?>
                                </span>
                                <p class="text-muted"><?= trans('message.auth.enterEmailAddress') ?></p>
                            </div>
                            <div class="pt-3" id="forgot">
                                <div class="form-group">
                                    <label class="form-label" for="email"><?= trans('translation.auth.email') ?>:</label>
                                    <input class="form-control" name="email"
                                        placeholder="<?= trans('translation.auth.enterYourEmail') ?>">
                                </div>
                                <div class="submit">
                                    <button class="btn btn-primary d-grid w-100"
                                        type="submit"><?= trans('translation.auth.submit') ?></button>
                                </div>
                                <div class="text-center mt-4">
                                    <p class="text-dark mb-0"><a class="text-primary ms-1"
                                            href="/login"><?= trans('translation.auth.backToLogin') ?></a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    @endsection()

    @section('scripts')
        <script>
            $(document).ready(function() {
                $("#forgotPasswordForm").validate({
                    rules: {
                        email: {
                            required: true,
                            regexEmail: true,
                            validate: `exists:users,email`,
                        },
                    },
                    messages: {
                        email: {
                            validate: trans('validation.exists', {
                                field: trans('translation.auth.email')
                            })
                        }
                    }
                });
            });
        </script>
    @endsection()
