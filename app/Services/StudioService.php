<?php

namespace App\Services;

use App\Models\Studio;

class StudioService
{
    public static function getStudioDataByUuid($uuid)
    {
        $studio = Studio::where("uuid", $uuid)->first()->toArray();
        return $studio;
    }

    public static function getUsersByStudio($uuid)
    {
        $studio = Studio::where('uuid', $uuid)->first();
        $users = $studio->users->toArray();

        return $users;
    }

    /**
     * Funçao estática que pega todos clientes ativos
     * vinculados ao UUID do studio.
    */
    public static function getCostumersByStudio($uuid)
    {
        $studio = Studio::where('uuid', $uuid)->first();
        $costumers = $studio->costumers->toArray();

        return $costumers;
    }
}
