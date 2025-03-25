<?php

namespace Tests\Seeder;

use App\Enums\PaymentTermTypeEnum;
use App\Enums\SaleOrderReceiptStatusEnum;
use App\Enums\SaleOrderStatusEnum;
use App\Models\SaleOrder;

class SaleOrderFactory
{
    public static function intSaleOrderFactory()
    {
        return SaleOrder::insert(
            [
                [
                    'id' => 2,
                    'customer_id' => 2,
                    'code' => 'S00002',
                    'invoice_code' => 'INV0002',
                    'receipt_code' => 'RG002-OUT-S00002',
                    'customer_email' => 'customer.tesr@gmail.com',
                    'customer_phone' => '+10123456789',
                    'customer_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'deliver_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'payment_term' => PaymentTermTypeEnum::FIFTEEN_DAYS,
                    'tax_rate' => 12.0,
                    'order_status' => SaleOrderStatusEnum::CONFIRM,
                    'receipt_status' => SaleOrderReceiptStatusEnum::DONE,
                    'note' => NULL,
                    'created_at' => '2024-08-09 08:26:06',
                    'updated_at' => '2024-08-12 13:53:24',
                    'delivery_method' => 1,
                    'tax_info' => '[{"code":"TPS","rate":5},{"code":"TVP","rate":7}]',
                    'warehouse_id' => 1
                ],
                [
                    'id' => 3,
                    'customer_id' => 2,
                    'code' => 'S00003',
                    'invoice_code' => 'INV0003',
                    'receipt_code' => 'RG003-OUT-S00003',
                    'customer_email' => 'customer.tesr@gmail.com',
                    'customer_phone' => '+10123456789',
                    'customer_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'deliver_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
                    'tax_rate' => 12.0,
                    'order_status' => SaleOrderStatusEnum::CONFIRM,
                    'receipt_status' => SaleOrderReceiptStatusEnum::DONE,
                    'note' => NULL,
                    'created_at' => '2024-08-09 09:39:24',
                    'updated_at' => '2024-08-09 09:39:24',
                    'delivery_method' => 2,
                    'tax_info' => '[{"code":"TPS","rate":5},{"code":"TVP","rate":7}]',
                    'warehouse_id' => 1
                ],
                [
                    'id' => 4,
                    'customer_id' => 1,
                    'code' => 'S00004',
                    'invoice_code' => 'INV0004',
                    'receipt_code' => 'RG004-OUT-S00004',
                    'customer_email' => 'customer.test@gmail.com',
                    'customer_phone' => '+10123456789',
                    'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
                    'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
                    'payment_term' => PaymentTermTypeEnum::FIFTEEN_DAYS,
                    'tax_rate' => 5.0,
                    'order_status' => SaleOrderStatusEnum::CONFIRM,
                    'receipt_status' => SaleOrderReceiptStatusEnum::READY,
                    'note' => NULL,
                    'created_at' => '2024-08-12 00:25:15',
                    'updated_at' => '2024-08-12 00:25:15',
                    'delivery_method' => 1,
                    'tax_info' => '[{"code":"TPS","rate":5}]',
                    'warehouse_id' => 1
                ],
                [
                    'id' => 5,
                    'customer_id' => 1,
                    'code' => 'S00005',
                    'invoice_code' => 'INV0005',
                    'receipt_code' => 'RG005-OUT-S00005',
                    'customer_email' => 'customer.test@gmail.com',
                    'customer_phone' => '+10123456789',
                    'customer_address' => 'Số 20, Airdrie, Alberta, Canada',
                    'deliver_address' => 'Số 20, Airdrie, Alberta, Canada',
                    'payment_term' => PaymentTermTypeEnum::TWENTY_DAYS,
                    'tax_rate' => 5.0,
                    'order_status' => SaleOrderStatusEnum::IN_TRANSIT,
                    'receipt_status' => SaleOrderReceiptStatusEnum::READY,
                    'note' => 'Hello',
                    'created_at' => '2024-08-12 02:16:13',
                    'updated_at' => '2024-08-12 02:16:13',
                    'delivery_method' => 2,
                    'tax_info' => '[{"code":"TPS","rate":5}]',
                    'warehouse_id' => 1
                ],
                [
                    'id' => 6,
                    'customer_id' => 2,
                    'code' => 'S00006',
                    'invoice_code' => 'INV0006',
                    'receipt_code' => 'RG006-OUT-S00006',
                    'customer_email' => 'customer.tesr@gmail.com',
                    'customer_phone' => '+10123456789',
                    'customer_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'deliver_address' => '400 rue de la Régie, Armstrong, British Columbia, Canada',
                    'payment_term' => PaymentTermTypeEnum::IMMEDIATE_PAYMENT,
                    'tax_rate' => 12.0,
                    'order_status' => SaleOrderStatusEnum::DRAFT,
                    'receipt_status' => SaleOrderReceiptStatusEnum::READY,
                    'note' => NULL,
                    'created_at' => '2024-08-12 13:43:05',
                    'updated_at' => '2024-08-12 13:43:05',
                    'delivery_method' => 2,
                    'tax_info' => '[{"code":"TPS","rate":5},{"code":"TVP","rate":7}]',
                    'warehouse_id' => 1
                ],
            ]
        );
    }
}
