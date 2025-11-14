<?php declare(strict_types=1);

namespace App\Domains\Vehicle\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Domains\Vehicle\Controller\Service\UpdateRole as ControllerService;

class UpdateRole extends ControllerAbstract
{
    public function __invoke(int $id): Response|RedirectResponse
    {
        $this->row($id);

        if ($response = $this->actionPost('updateRole')) {
            return $response;
        }

        $this->meta('title', __('vehicle-update-role.meta-title', [
            'title' => $this->row->name
        ]));

        return $this->page('vehicle.update-role', $this->data());
    }

    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth, $this->row)->data();
    }

    protected function updateRole(): RedirectResponse
    {
        $related = $this->request->input('related', []);

        $this->row->roles()->sync($related);

        $this->sessionMessage('success', "Successful update role");

        return redirect()->route('vehicle.update.role', $this->row->id);
    }
}
