<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: right;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header img {
            float: left;
            width: 100px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .footer-pdf {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }

        .footer-view-pdf {
            width: 100%;
            font-size: 12px;
            border-top: 1px solid #ddd;
            clear: both;
        }

        .payment-instruction {
            margin-top: 10px;
        }

        .title {
            text-transform: uppercase
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents(public_path('assets/images/brand/small-logo.png'))); ?>" alt="Company Logo">
            <h1 class="title">{{ $title }}</h1>
            <p>Les Remorques du Nord<br>les@example.co | +64 123 4567 890</p>
        </div>
        @yield('content')
        <div class="{{ !empty($viewPdf) ? 'footer-view-pdf' : 'footer-pdf' }}">
            <div class="payment-instruction">
                <strong>{{ __('translation.pdf.paymentInstruction') }}</strong><br>
                Les Remorques du Nord<br>
                {{ __('translation.pdf.bankName') }}: ABC Bank Limited<br>
                {{ __('translation.pdf.accountNumber') }}: 12-1234-123456-12<br>
            </div>
            <p>{{ __('translation.pdf.footer', ['email' => 'les@example.co']) }}</p>
        </div>
    </div>
</body>

</html>
