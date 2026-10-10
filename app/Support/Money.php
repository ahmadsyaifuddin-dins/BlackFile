<?php

namespace App\Support;

/**
 * Helper format mata uang untuk fitur Finance.
 *
 * idr()     -> "Rp 1.250.000"
 * compact() -> versi ringkas untuk kartu ringkasan: "Rp 1,3 jt"
 */
class Money
{
    /**
     * Format angka penuh dengan pemisah ribuan Indonesia.
     */
    public static function idr($amount, bool $prefix = true): string
    {
        $value = number_format((float) $amount, 0, ',', '.');

        return $prefix ? 'Rp '.$value : $value;
    }

    /**
     * Format ringkas (juta / miliar / triliun) untuk keterbatasan ruang.
     */
    public static function compact($amount, bool $prefix = true): string
    {
        $value = (float) $amount;
        $sign = $value < 0 ? '-' : '';
        $abs = abs($value);

        if ($abs >= 1_000_000_000_000) {
            $formatted = rtrim(rtrim(number_format($abs / 1_000_000_000_000, 1, ',', '.'), '0'), ',').' T';
        } elseif ($abs >= 1_000_000_000) {
            $formatted = rtrim(rtrim(number_format($abs / 1_000_000_000, 1, ',', '.'), '0'), ',').' M';
        } elseif ($abs >= 1_000_000) {
            $formatted = rtrim(rtrim(number_format($abs / 1_000_000, 1, ',', '.'), '0'), ',').' jt';
        } else {
            $formatted = number_format($abs, 0, ',', '.');
        }

        return ($prefix ? 'Rp ' : '').$sign.$formatted;
    }
}
