<?php

namespace Tests\Seeder;

use App\Enums\ReturnOrderStatusEnum;
use App\Models\ReturnOrder;
use Carbon\Carbon;

class ReturnOrderFactory
{
    public static function intReturnOrderFactory()
    {
        return ReturnOrder::insert(
            [
                [
                    'id' => 1,
                    'status' => ReturnOrderStatusEnum::READY,
                    'purchase_order_id' => 5,
                    'scheduled_date' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"),
                    'note' => '',
                    'code' => 'WH5-OUT-P00005-01',
                    'created_at' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 2,
                    'status' => ReturnOrderStatusEnum::READY,
                    'purchase_order_id' => 5,
                    'scheduled_date' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"),
                    'note' => '',
                    'code' => 'WH5-OUT-P00005-02',
                    'created_at' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 3,
                    'status' => ReturnOrderStatusEnum::CANCEL,
                    'purchase_order_id' => 5,
                    'scheduled_date' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"),
                    'note' => '',
                    'code' => 'WH5-OUT-P00005-03',
                    'created_at' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s")
                ],
                [
                    'id' => 4,
                    'status' => ReturnOrderStatusEnum::DONE,
                    'purchase_order_id' => 5,
                    'scheduled_date' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s"),
                    'note' => '',
                    'code' => 'WH5-OUT-P00005-04',
                    'created_at' => Carbon::parse('2024-06-27')->format("Y-m-d H:i:s")
                ]
            ]
        );
    }
}
