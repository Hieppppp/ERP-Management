<?php

namespace App\Helpers;

use DateTime;

class SaleOrderHelper
{

    /**
     * Calculate Payment Term
     *
     * @param  string $createdDate
     * @param  string|int $paymentTerm
     * @return DateTime
     */
    public static function calculatePaymentTerm(string $createdDate, string|int $paymentTerm): DateTime
    {
        $data = [
            ['id' => '1', 'day' => 0],
            ['id' => '2', 'day' => 15],
            ['id' => '3', 'day' => 20],
            ['id' => '4', 'day' => 30],
            ['id' => '5', 'day' => 45],
            ['id' => '6', 'day' => null],
        ];

        $date = new DateTime($createdDate);
        $paymentTermDays = null;

        foreach ($data as $item) {
            if ($item['id'] == $paymentTerm) {
                $paymentTermDays = $item['day'];
                break;
            }
        }

        if (is_null($paymentTermDays)) {
            return $date->modify('last day of +2 months');
        }

        return $date->modify("+{$paymentTermDays} days");
    }
}
