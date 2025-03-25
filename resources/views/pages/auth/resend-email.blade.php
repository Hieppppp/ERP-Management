@extends('layouts.custom-main')
@section('styles')
@endsection

@section('body')

    <body class="ltr login-img">
    @endsection()

    @section('content')
        <!-- CONTAINER OPEN -->
        <div class="col mx-auto text-center">
            <a href="{{ url('index') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img" alt="">
            </a>
        </div>
        <div class="col-12 container-login100">
            <div class="row">
                <div class="col col-login mx-auto">
                    <form class="card shadow-none" method="post" action="<?= url('/forgot-password'); ?>">
                        <div class="card-body" style="max-width:400px;">
                            <div class="text-center">
                                <span class="login100-form-title">
                                    <?= trans('translation.auth.forgotPassword') ?>
                                </span>
                                <p class="text-dark"><?= trans('translation.auth.sendEmailSuccess') ?></p>
                            </div>
                            <div>
                                <div class="text-center mt-4">
                                    <p class="text-dark mb-0"><?= trans('translation.auth.notReceiveEmail') ?></p>
                                </div>
                                <div class="text-center ">
                                    <span id="waitToSend" class="text-dark mb-0"><?= trans('translation.auth.wait') . ' '?><span id="timer"></span> <?=  ' ' . trans('translation.auth.toResend')?></span>
                                </div>
                            </div>
                            <div class="pt-3" id="forgot">
                                <div class="text-center mt-4">
                                    <p class="text-dark mb-0"><a class="text-primary ms-1" href="/login"><?= trans('translation.auth.backToLogin') ?></a></p>
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
    <script src="{{ asset('assets/plugins/notify/js/jquery.growl.js') }}"></script>

    <script>
        var countdown;
        function startCountdown(duration, display) {
            var timer = duration;
            var minutes, seconds;

            countdown = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(countdown);
                    document.getElementById('waitToSend').innerHTML = `<span onclick="sendMail()" id="toSendEmail" class="text-primary ms-1" style="cursor: pointer;"><?= trans('translation.auth.resend') ?></span>`;
                }
            }, 1000);
        }

        function restartCountdown(duration, display) {
            clearInterval(countdown);
            startCountdown(duration, display);
        }

        function notification(type, message) {
            if (type == 'success') {
                return $.growl.notice({
                    title: trans('message.success'),
                    message: message
                });
            }

            if (type == 'error') {
                return $.growl.error({
                    title: trans('message.error'),
                    message: message
                });
            }
        }

        window.onload = function () {
            var fiveMinutes = <?= $time ?>;
            var display = document.getElementById('timer');
            startCountdown(fiveMinutes, display);


        };
        function sendMail() {
            $('#global-loader').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `/resend-email`,
                type: "POST",
                success: function (response, textStatus, xhr) {
                    notification("success", response.message[0]);
                    document.getElementById('toSendEmail').innerHTML = `<span id="waitToSend" class="text-dark mb-0"><?= trans('translation.auth.wait') . ' '?><span id="timer"></span> <?=  ' ' . trans('translation.auth.toResend')?></span>`;
                    restartCountdown(response.data.data, document.getElementById('timer'));
                    $('#global-loader').hide();
                },
                error: function (e) {
                    $('#global-loader').hide();
                    if (e.responseJSON?.redirect) {
                        window.location.href = e.responseJSON?.redirect;
                    } else {
                         notification("error", e.responseJSON.message[0]);
                    }
                },
            });
        }

    </script>
    @endsection()
