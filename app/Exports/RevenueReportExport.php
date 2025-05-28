<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RevenueReportExport implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return SaleOrder::select(
                'sale_orders.code',
                'sale_orders.invoice_code',
                'sale_orders.receipt_code',
                'sale_orders.customer_id',
                'sale_orders.customer_email',
                'sale_orders.order_status',
                'sale_orders.created_at',
                DB::raw("concat(c.first_name, ' ', c.last_name) as customer_name"),
                DB::raw("sum(sod.quantity) as total_quantity"),
                DB::raw("ROUND(SUM(sod.quantity * sod.unit_price * (100 - sod.discount_rate) / 100) * (1 + sale_orders.tax_rate / 100), 2) as total_amount")
            )
            ->join('customers as c', 'c.id', '=', 'sale_orders.customer_id')
            ->leftJoin('sale_order_details as sod', 'sod.sale_order_id', '=', 'sale_orders.id')
            ->groupBy(
                'sale_orders.code',
                'sale_orders.invoice_code',
                'sale_orders.receipt_code',
                'sale_orders.customer_id',
                'sale_orders.customer_email',
                'sale_orders.order_status',
                'sale_orders.created_at',
                'c.first_name',
                'c.last_name'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Order Code',
            'Invoice Code',
            'Receipt Code',
            'Customer ID',
            'Customer Email',
            'Order Status',
            'Created At',
            'Customer Name',
            'Total Quantity',
            'Total Amount'
        ];
    }

    public function title(): string
    {
        return "Revenue Report";
    }
}
