<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512\Parser;

use App\Services\Protocol\ParserAbstract;

class Login extends ParserAbstract
{
    public function resources(): array
    {
        $serial = substr($this->message, 22, 4); // extract serial
        $payload = '0501' . $serial;

        $crc = strtoupper(dechex(crc16(hex2bin($payload))));
        $crc = str_pad($crc, 4, '0', STR_PAD_LEFT);

        $ack = '7878' . $payload . $crc . '0D0A';

        $this->resources[] = [
            'message' => $this->message,
            'serial' => $serial,
            'response' => hex2bin($ack),
            'data' => [],
        ];

        return $this->resources;
    }
}
