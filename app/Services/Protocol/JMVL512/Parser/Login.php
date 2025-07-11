<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512\Parser;

use App\Services\Protocol\ParserAbstract;
use App\Services\Protocol\Resource\Auth as ResourceAuth;


class Login extends ParserAbstract
{

    public function resources(): array
    {
        $this->message = "787811010861652050026877804312c10006cc8c0d0a";

        $serial = substr($this->message, 22, 4); // serial = "cc8c"
        $responses = [
            'ACCREP9d',
            'TIMER,2,60',
            'SPEED,OFF',
            'POWERALM,OFF',
            'EXBATALM,OFF',
            'BATALM,OFF',
            'INSTALLALM,OFF',
        ];

        foreach ($responses as $text) {
            $payload = bin2hex($text);
            $len = strlen($payload) / 2;
            $lenHex = strtoupper(str_pad(dechex($len), 2, '0', STR_PAD_LEFT));

            $packet = "7878" . $lenHex . $payload;
            $crc = strtoupper(dechex($this->crc16(hex2bin($lenHex . $payload))));
            $crc = str_pad($crc, 4, '0', STR_PAD_LEFT);
            $packet .= $crc . "0D0A";

            $this->resources[] = new ResourceAuth([
                'message' => $this->message,
                'serial' => $serial,
                'response' => hex2bin($packet),
                'data' => [],
            ]);
        }

        return $this->resources;
    }


    protected function crc16(string $data): int
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? ($crc << 1) ^ 0x1021 : ($crc << 1);
            }
            $crc &= 0xFFFF;
        }
        return $crc;
    }

}
