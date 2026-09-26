<?php

namespace Tests\Unit;

use App\Helpers\Terbilang;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TerbilangTest extends TestCase
{
    public static function nominalProvider(): array
    {
        return [
            [0, 'Nol Rupiah'],
            [11, 'Sebelas Rupiah'],
            [15, 'Lima Belas Rupiah'],
            [100, 'Seratus Rupiah'],
            [1000, 'Seribu Rupiah'],
            [1046500, 'Satu Juta Empat Puluh Enam Ribu Lima Ratus Rupiah'],
            [67131073, 'Enam Puluh Tujuh Juta Seratus Tiga Puluh Satu Ribu Tujuh Puluh Tiga Rupiah'],
            [70870573, 'Tujuh Puluh Juta Delapan Ratus Tujuh Puluh Ribu Lima Ratus Tujuh Puluh Tiga Rupiah'],
            [2000000000, 'Dua Miliar Rupiah'],
            [1500.6, 'Seribu Lima Ratus Satu Rupiah'],
        ];
    }

    #[DataProvider('nominalProvider')]
    public function test_rupiah(float|int $nominal, string $expected): void
    {
        $this->assertSame($expected, Terbilang::rupiah($nominal));
    }
}
