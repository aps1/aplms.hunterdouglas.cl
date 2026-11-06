<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\MediaLibrary\Models\Media;

class MediaPolicy
{
    use HandlesAuthorization;

    /**
     * valida descarga de media para las actividades
     *
     * @return void
     */
    public function view(User $user, Media $media)
    {
        // El admin puede ver cualquiera
        if ($user->isAdminLevel()) {
            return true;
        }
        $leads_ids = $user->dealership->leads()->pluck('id')->toArray();
        // si es de otro paias el global scope da nulo para el media model
        if (! $media->model) {
            return false;
        }

        // revisa si el lead del adjunto es del distribuidor
        return in_array($media->model->lead_id, $leads_ids);

    }
}
