<?php declare(strict_types=1);

namespace App\Domains\Vehicle\Controller\Service;

use App\Domains\Vehicle\Model\Vehicle;
use Spatie\Permission\Models\Role;

class UpdateRole
{
    public function __construct(
        protected $request,
        protected $auth,
        protected Vehicle $row
    ) {}

    public static function new($request, $auth, Vehicle $row): static
    {
        return new static($request, $auth, $row);
    }

    public function data(): array
    {
        return [
            'row' => $this->row,
            'roles' => Role::orderBy('name')->get(),
        ];
    }
}
