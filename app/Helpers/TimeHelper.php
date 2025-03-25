<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{

    /**
     * Time Since
     *
     * @param $date
     * @return string
     */
    public static function timeSince($date): string
    {
        $now = Carbon::now();
        $date = Carbon::parse($date);

        $diffInSeconds = floor(($date->diffInSeconds($now)));

        if ($diffInSeconds <= 0) {
            return '0 ' . __('translation.time.lastSecond');
        }

        if ($diffInSeconds >= 31536000) {
            $years = floor(($date->diffInYears($now)));
            return $years . ' ' . __('translation.time.lastYear');
        }

        if ($diffInSeconds >= 2592000) {
            $months = floor(($date->diffInMonths($now)));
            return $months . ' ' . __('translation.time.lastMonth');
        }

        if ($diffInSeconds >= 86400) {
            $days = floor(($date->diffInDays($now)));
            return $days . ' ' . __('translation.time.lastDay');
        }

        if ($diffInSeconds >= 3600) {
            $hours = floor(($date->diffInHours($now)));
            return $hours . ' ' . __('translation.time.lastHour');
        }

        if ($diffInSeconds >= 60) {
            $minutes = floor(($date->diffInMinutes($now)));
            return $minutes . ' ' . __('translation.time.lastMinute');
        }

        return $diffInSeconds . ' ' . __('translation.time.lastSecond');
    }
}
