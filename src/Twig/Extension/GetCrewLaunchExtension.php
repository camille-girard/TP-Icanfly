<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GetCrewLaunchRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetCrewLaunchExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getCrewLaunch', [GetCrewLaunchRuntime::class, 'getCrewLaunchRuntime']),
        ];
    }
}
