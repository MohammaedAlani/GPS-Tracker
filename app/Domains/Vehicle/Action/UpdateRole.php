<?php declare(strict_types=1);

namespace App\Domains\Vehicle\Action;

use App\Domains\Role\Model\Role as RoleModel;
use App\Domains\Vehicle\Model\Vehicle as Model;
use Spatie\Permission\Models\Role;

class UpdateRole extends ActionAbstract
{
    /**
     * @return \App\Domains\Vehicle\Model\Vehicle
     */
    public function handle(): Model
    {
        $this->data();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['related'] = Role::findById($this->data['related'])
            ->pluck('id')
            ->all();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveUnrelate();
        $this->saveRelate();
    }

    /**
     * @return void
     */
    protected function saveUnrelate(): void
    {
        $this->row->roles()->detach();
    }

    /**
     * @return void
     */
    protected function saveRelate(): void
    {
        if (empty($this->data['related'])) {
            return;
        }

        $this->row->roles()->sync($this->data['related']);
    }
}
