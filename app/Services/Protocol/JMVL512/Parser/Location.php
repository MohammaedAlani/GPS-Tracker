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

        // TODO: Parse GPS timestamp, lat/lon, speed, etc.

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
