<?php

namespace App\Constants;

use Carbon\Carbon;

class LogBookConst
{
    const STATUS_BELUM_DIISI = 'empty';

    const STATUS_MENUNGGU_REVIEW = 'menunggu_review';

    const STATUS_SUDAH_REVIEW = 'sudah_direview';

    const STATUS_HARI_LIBUR = 'holiday';

    public static function getFilterStatusOptions(): array
    {
        return [
            self::STATUS_BELUM_DIISI => 'Belum Diisi',
            self::STATUS_MENUNGGU_REVIEW => 'Belum Direview',
            self::STATUS_SUDAH_REVIEW => 'Sudah Direview',
            self::STATUS_HARI_LIBUR => 'Hari Libur',
        ];
    }

    /**
     * Opsi bulan (1-12) untuk filter index.
     *
     * @return array<int, string>
     */
    public static function getMonthOptions(): array
    {
        $months = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create(null, $m)->translatedFormat('F');
        }

        return $months;
    }
}
