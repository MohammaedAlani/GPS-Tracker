<?php declare(strict_types=1);

namespace App\Models;

use App\Domains\Vehicle\Model\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class VehicleRole extends Model
{
    protected $table = 'vehicle_role';

    protected $fillable = [
        'vehicle_id',
        'role_id',
    ];

    public $timestamps = false;

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
