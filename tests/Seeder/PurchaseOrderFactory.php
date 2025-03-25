<?php

namespace Tests\Seeder;

use App\Enums\PurchaseOrderStatusEnum;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class PurchaseOrderFactory
{
    public static function intPurchaseOrderFactory()
    {
        return PurchaseOrder::insert(
            [
                [
                    'id' => 1,
                    'supplier_id' => 1,
                    'warehouse_id' => 1,
                    'created_by' => 1,
                    'scheduled_date' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::DRAFT,
                    'received_note' => null,
                    'code' => 'P00001',
                    'batch_code' => null,
                    'receipt_code' => null
                ],
                [
                    'id' => 2,
                    'supplier_id' => 1,
                    'warehouse_id' => 2,
                    'created_by' => 1,
                    'scheduled_date' => Carbon::parse('2024-06-28')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-25')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::PENDING,
                    'received_note' => null,
                    'code' => 'P00002',
                    'batch_code' => null,
                    'receipt_code' => 'WH2-IN-P00002'
                ],
                [
                    'id' => 3,
                    'supplier_id' => 2,
                    'warehouse_id' => 3,
                    'created_by' => 2,
                    'scheduled_date' => Carbon::parse('2024-06-16')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-15')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::PENDING_SHELVE,
                    'received_note' => "Has received the goods",
                    'code' => 'P00003',
                    'batch_code' => 'BATCH0003',
                    'receipt_code' => 'WH3-IN-P00003'
                ],
                [
                    'id' => 4,
                    'supplier_id' => 2,
                    'warehouse_id' => 4,
                    'created_by' => 2,
                    'scheduled_date' => Carbon::parse('2024-06-17')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-14')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::CANCEL,
                    "received_note" => "",
                    'code' => 'P00004',
                    'batch_code' => null,
                    'receipt_code' => 'WH4-IN-P00004'
                ],
                [
                    'id' => 5,
                    'supplier_id' => 3,
                    'warehouse_id' => 5,
                    'created_by' => 1,
                    'scheduled_date' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::DONE,
                    'received_note' => "Has received the goods",
                    'code' => 'P00005',
                    'batch_code' => 'BATCH0005',
                    'receipt_code' => 'WH5-IN-P00005'
                ],
                [
                    'id' => 6,
                    'supplier_id' => 3,
                    'warehouse_id' => 6,
                    'created_by' => 1,
                    'scheduled_date' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::PENDING,
                    'received_note' => "",
                    'code' => 'P00006',
                    'batch_code' => null,
                    'receipt_code' => 'WH6-IN-P00006'
                ],
                [
                    'id' => 7,
                    'supplier_id' => 3,
                    'warehouse_id' => 5,
                    'created_by' => 1,
                    'scheduled_date' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'created_at' => Carbon::parse('2024-06-26')->format("Y-m-d H:i:s"),
                    'status' => PurchaseOrderStatusEnum::DONE,
                    'received_note' => "Has received the goods",
                    'code' => 'P00007',
                    'batch_code' => 'BATCH0007',
                    'receipt_code' => 'WH5-IN-P00007'
                ],
            ]
        );
    }
}
