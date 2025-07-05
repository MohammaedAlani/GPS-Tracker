<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512\Parser;

use App\Services\Protocol\ParserAbstract;

class Login extends ParserAbstract
{
    public function resources(): array
    {
        $hex = bin2hex($this->message);

        if (substr($hex, 6, 2) !== '01') {
            return [];
        }

        $imeiHex = substr($hex, 8, 16);
        $imei = ltrim(gmp_strval(gmp_init($imeiHex, 16)), '0');

        return [[
            'type' => 'login',
            'device_id' => $imei,
            'raw' => $hex,
        ]];
    }
}
