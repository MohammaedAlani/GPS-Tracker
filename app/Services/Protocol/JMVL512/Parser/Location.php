<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512\Parser;

use App\Services\Protocol\ParserAbstract;

class Location extends ParserAbstract
{
    public function resources(): array
    {
        $hex = bin2hex($this->message);

        if (substr($hex, 6, 2) !== '12') {
            return [];
        }

        // Extract device ID from the hex string
        $deviceIdHex = substr($hex, 8, 16);
        $deviceId = ltrim(gmp_strval(gmp_init($deviceIdHex, 16)), '0');
        // Extract latitude and longitude from the hex string
        $latitudeHex = substr($hex, 24, 8);
        $longitudeHex = substr($hex, 32, 8);

        return [[
            'type' => 'location',
            'device_id' => 'TO_BE_PARSED',
            'latitude' => 0,
            'longitude' => 0,
            'speed' => 0,
            'timestamp' => now(),
            'raw' => $hex,
        ]];
    }
}
