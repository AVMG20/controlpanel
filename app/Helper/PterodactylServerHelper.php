<?php

namespace App\Helper;

use App\Models\Configuration;
use App\Models\Pterodactyl\Egg;
use App\Models\Pterodactyl\Location;
use App\Models\User;

class PterodactylServerHelper
{
    /**
     * Data used for the Pterodactyl create server endpoint
     *
     * @param string $name
     * @param User $user
     * @param Egg $egg
     * @param Location $location
     * @param Configuration $configuration
     * @return array
     */
    public function createServerData(string $name, User $user, Egg $egg, Location $location, Configuration $configuration): array
    {
        return [
            "name" => $name,
            "user" => $user->pterodactyl_id,
            "nest" => $egg->nest->id,
            "egg" => $egg->id,
            "docker_image" => $egg->docker_image,
            "startup" => $egg->startup,
            "limits" => [
                "memory" => $configuration->memory,
                "swap" => $configuration->memory,
                "disk" => $configuration->disk,
                "io" => $configuration->io,
                "cpu" => $configuration->cpu
            ],
            "environment" => $egg->getEnvironmentVariables(),
            "feature_limits" => [
                "databases" => $configuration->databases,
                "allocations" => $configuration->allocations,
                "backups" => $configuration->backups
            ],
            "deploy" => [
                "locations" => [
                    $location->id
                ],
                "dedicated_ip" => false,
                "port_range" => [
                ]
            ],
            "start_on_completion" => false
        ];
    }
}
