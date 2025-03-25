<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        p {
            margin-block-end: 0 !important;
            margin-block-start: 0 !important;
        }
    </style>
</head>

<body>
    <p>Dear {{ $saleOrder->customer->first_name . ' ' . $saleOrder->customer->last_name }},</p>

    <p>Hear is your invoice {{ $saleOrder->invoice_code }} amounting in CAD
        {{ number_format($totalAmountUnpaid, 2) }}$ from Les Remorques du Nord.</p>

    <p>Please remit the payment and do not hesitate to contact us if you have any questions.</p>
    <br>
    <p>Best regards,</p>
    <p><strong style="color: #82C035;">Les Remorques du Nord</strong></p>
</body>

</html>
