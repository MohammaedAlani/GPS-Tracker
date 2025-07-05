<?php declare(strict_types=1);

namespace App\Services\Protocol\JMVL512;

use App\Services\Protocol\JMVL512\Parser\Login;
use App\Services\Protocol\JMVL512\Parser\Location;
use App\Services\Protocol\JMVL512\Parser\Heartbeat;
use App\Services\Protocol\ProtocolAbstract;
use App\Services\Server\Socket\Server;

class Manager extends ProtocolAbstract
{
    public function code(): string
    {
        return 'jmvl512';
    }

    public function name(): string
    {
        return 'JM-VL512';
    }

    public function server(int $port): Server
    {
        return Server::new($port)
            ->socketType('stream') // JM-VL512 uses TCP
            ->socketProtocol('ip');
    }

    public function messages(string $message): array
    {
        return [$message]; // Each JM-VL512 packet is self-contained
    }

    protected function parsers(): array
    {
        return [
            Login::class,
            Location::class,
            Heartbeat::class,
        ];
    }
}
