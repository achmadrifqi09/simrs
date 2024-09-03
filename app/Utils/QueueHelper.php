<?php

namespace App\Utils;
use Carbon\Carbon;
class QueueHelper
{
    public static function createQueueCode(string $openPracticeHour) : string
    {
        $openPracticeHour = Carbon::createFromFormat('H:i:s', $openPracticeHour);
        $noon = Carbon::createFromFormat('H:i:s', '12:00:00');

        return $openPracticeHour->lessThan($noon) ? 'A' : 'B';
    }

    public static function generateServiceTime(int $queueTotal, string $checkupDate, string $openPracticeTime): float|int
    {
        $estimateTime = (int) env('ESTIMATE_SERVICE_TIME');
        $practiceTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $checkupDate . ' ' . $openPracticeTime,
            'Asia/Jakarta'
        );

        $resultInString = $practiceTime->addMinutes($queueTotal * $estimateTime)->toDateTimeString();
        $resultInTime = Carbon::parse($resultInString);
        return $resultInTime->timestamp * 1000;
    }



}
