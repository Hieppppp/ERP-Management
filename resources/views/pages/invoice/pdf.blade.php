@extends('layouts/pdf/invoice-layout')


@section('styles')
    <style>
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            font-size: 12px;
        }

        .invoice-box table tr td {
            padding-bottom: 5px;
            padding-top: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #ddd;
            border-bottom: 1px solid #ddd;
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .total-amount {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            float: right;
        }

        .customer-name {
            width: 100%;
            text-align: right;
        }

        .pading-left-50 {
            padding-left: 50px;
        }

        .pading-right-50 {
            padding-right: 50px;
        }

        .top {
            padding-bottom: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-amount table tr.item td:nth-child(1) {
            padding-right: 40px;
        }

        .td-top {
            width: 22.5%;
        }
    </style>
@endsection()

@section('content')
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="6">
                    <table style="margin-bottom: 30px;">
                        <tr>
                            <td colspan="6" class="customer-name">
                                {{ $saleOrder->customer->first_name . ' ' . $saleOrder->customer->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="td-top title">{{ __('translation.invoice.invoiceNumber') }} :</td>
                            <td class="td-top">{{ $saleOrder->invoice_code }}</td>
                            <td></td>
                            <td></td>
                            <td class="td-top title">{{ __('translation.invoice.phoneNumber') }} :</td>
                            <td class="text-right td-top">{{ $saleOrder->customer->phone }}</td>
                        </tr>
                        <tr colspan="6">
                            <td class="td-top title">{{ __('translation.invoice.invoiceDate') }} :</td>
                            <td class="td-top">{{ $saleOrder->created_at }}</td>
                            <td></td>
                            <td></td>
                            <td class="td-top title">{{ __('translation.invoice.emailAddress') }} :</td>
                            <td class="text-right td-top">{{ $saleOrder->customer->email }}</td>
                        </tr>
                        <tr colspan="6">
                            <td class="td-top title">{{ __('translation.invoice.due') }} :</td>
                            <td class="td-top">{{ $paymentTerm }}</td>
                            <td></td>
                            <td></td>
                            <td class="td-top title">{{ __('translation.invoice.address') }} :</td>
                            <td class="text-right td-top">{{ $saleOrder->customer_address }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>{{ __('translation.invoice.code') }}</td>
                <td class="text-center">{{ __('translation.invoice.name') }}</td>
                <td class="text-center">{{ __('translation.invoice.orderQuantity') }}</td>
                <td class="text-right">{{ __('translation.invoice.unitPrice') }}</td>
                <td class="text-center">{{ __('translation.invoice.disc') }}.%</td>
                <td class="text-right">{{ __('translation.invoice.totalPrice') }} (CAD)</td>
            </tr>
            @foreach ($saleOrderDetails as $saleOrderDetail)
                <tr class="item">
                    <td>{{ $saleOrderDetail->code }}</td>
                    <td class="text-center">{{ $saleOrderDetail->name }}</td>
                    <td class="text-center">{{ $saleOrderDetail->order_quantity }}</td>
                    <td class="text-right">{{ $saleOrderDetail->order_unit_price }}$</td>
                    <td class="text-center">{{ $saleOrderDetail->discount_rate }}%</td>
                    <td class="text-right">
                        {{ number_format(
                            ($saleOrderDetail->order_quantity * $saleOrderDetail->order_unit_price * (100 - $saleOrderDetail->discount_rate)) /
                                100,
                            2,
                        ) }}$
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="total-amount">
            <table cellpadding="0" cellspacing="0">
                <tr class="item">
                    <td>{{ __('translation.invoice.tax') }}: </td>
                    <td>{{ $totalTax }}%</td>
                </tr>
                <tr class="item">
                    <td>{{ __('translation.invoice.totalAmount') }}: </td>
                    <td>{{ number_format($totalAmount, 2) }}$</td>
                </tr>
                <tr class="item">
                    <td>{{ __('translation.invoice.creditCardFee') }}: </td>
                    <td>0.00$</td>
                </tr>
                @if (count($registerPayments) > 0)
                    @foreach ($registerPayments as $registerPayment)
                        <tr class="item">
                            <td>{{ __('translation.invoice.paidOn') }} {{ $registerPayment['date'] }}:</td>
                            <td>{{ number_format($registerPayment['paid_amount'], 2) }}$</td>
                        </tr>
                    @endforeach
                @endif
                <tr class="item">
                    <td>{{ __('translation.invoice.amountDueOn') }} {{ $paymentTerm }}:</td>
                    <td>{{ number_format(abs($totalAmountUnpaid), 2) }}$</td>
                </tr>
            </table>
        </div>
    </div>
@endSection()
