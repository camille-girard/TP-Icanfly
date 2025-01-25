<?php

namespace App\Twig\Runtime;

use App\Service\SpaceXServiceAPI;
use Twig\Extension\RuntimeExtensionInterface;

class GetCrewLaunchRuntime implements RuntimeExtensionInterface
{
    private SpaceXServiceAPI $spaceXService;

    public function __construct(SpaceXServiceAPI $spaceXService)
    {
        $this->spaceXService = $spaceXService;
    }

    public function getCrewLaunchRuntime(string $crewid): array
    {
        try {
            return $this->spaceXService->getCrewMembers($crewid);
        } catch (\Exception $e) {
            return [];
        }
    }
}
