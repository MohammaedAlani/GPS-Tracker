<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512\Parser;

use App\Services\Protocol\ParserAbstract;

class Heartbeat extends ParserAbstract
{
    public function resources(): array
    {
        $hex = bin2hex($this->message);

        if (substr($hex, 6, 2) !== '13') {
            return [];
        }

        return [[
            'type' => 'heartbeat',
            'device_id' => 'UNKNOWN', // unless encoded
            'timestamp' => now(),
            'raw' => $hex,
        ]];
    }
}
