<?php declare(strict_types=1);

use App\Domains\Device\Model\Device;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('device_packet_log', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Device::class);
            $table->binary('data');
            $table->unsignedBigInteger('length');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('device_packet_log');
    }
};
