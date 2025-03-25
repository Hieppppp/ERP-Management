@extends('layouts/pdf/invoice-layout')


@section('styles')
    <style>
        .supplier-details,
        .order-details {
            margin-bottom: 20px;
        }

        .supplier-details {
            float: left;
            width: 50%;
        }

        .order-details {
            float: right;
            width: 50%;
            text-align: right;
        }

        .details {
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-size: 14px;
            margin-top: 10px;
        }

        .text-muted {
            color: #707070 !important;
            --bs-text-opacity: 1;
        }
    </style>
@endsection()

@section('content')
    <div class="supplier-details">
        <p class="details">
            <strong>{{ $purchaseOrder->supplier->name }}</strong><br>
            {{ __('translation.purchaseOrder.orderNumber') }}: {{ $purchaseOrder->code }}<br>
            {{ __('translation.purchaseOrder.orderDate') }}: {{ $purchaseOrder->created_at }}<br>
            {{ __('translation.purchaseOrder.orderDue') }}: {{ $purchaseOrder->scheduled_date }}
        </p>
    </div>

    <div class="order-details">
        <p class="details">
            <strong>{{ strtoupper(__('translation.purchaseOrder.shipTo')) }}:
                {{ $purchaseOrder->warehouse->name }}</strong><br>
            <span class="text-muted">{{ $purchaseOrder->warehouse->address }}</span><br>
            <span class="text-muted">
                {{ __('translation.warehouse.contact') . ': ' . ($purchaseOrder->warehouse?->contact ?? '') }}</span>
        </p>
    </div>

    <div style="clear: both;"></div>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('translation.product.code') }}</th>
                <th>{{ __('translation.product.name') }}</th>
                <th>{{ __('translation.product.orderQuantity') }}</th>
                <th style="text-align: right;">{{ __('translation.product.unitCost') }} (CAD)</th>
                <th style="text-align: right;">{{ __('translation.product.totalPrice') }} (CAD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td style="text-align: right;">{{ $product->unit_cost }}$</td>
                    <td style="text-align: right;">{{ number_format($product->quantity * $product->unit_cost, 2) }}$</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">
        <strong>{{ __('translation.product.totalAmount') }} (CAD):</strong> {{ number_format($totalAmount, 2) }}$
    </p>
@endSection()
