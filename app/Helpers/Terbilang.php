<?php

namespace App\Helpers;

class Terbilang
{
    private const SATUAN = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima',
        'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas',
    ];

    /**
     * Ubah nominal ke kalimat rupiah, mis. 70870573 →
     * "Tujuh Puluh Juta Delapan Ratus Tujuh Puluh Ribu Lima Ratus Tujuh Puluh Tiga Rupiah".
     */
    public static function rupiah(float|int $nominal): string
    {
        $angka = (int) round(abs($nominal));

        $kata = $angka === 0 ? 'nol' : self::eja($angka);

        return ucwords(($nominal < 0 ? 'minus ' : '').$kata.' rupiah');
    }

    private static function eja(int $n): string
    {
        return trim(match (true) {
            $n < 12 => self::SATUAN[$n],
            $n < 20 => self::eja($n - 10).' belas',
            $n < 100 => self::eja(intdiv($n, 10)).' puluh '.self::eja($n % 10),
            $n < 200 => 'seratus '.self::eja($n - 100),
            $n < 1000 => self::eja(intdiv($n, 100)).' ratus '.self::eja($n % 100),
            $n < 2000 => 'seribu '.self::eja($n - 1000),
            $n < 1_000_000 => self::eja(intdiv($n, 1000)).' ribu '.self::eja($n % 1000),
            $n < 1_000_000_000 => self::eja(intdiv($n, 1_000_000)).' juta '.self::eja($n % 1_000_000),
            $n < 1_000_000_000_000 => self::eja(intdiv($n, 1_000_000_000)).' miliar '.self::eja($n % 1_000_000_000),
            default => self::eja(intdiv($n, 1_000_000_000_000)).' triliun '.self::eja($n % 1_000_000_000_000),
        });
    }
}
