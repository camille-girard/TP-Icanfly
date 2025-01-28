<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpaceXServiceAPI
{
    private const BASE_URL = 'https://api.spacexdata.com/v5/launches';
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    private function fetchLaunches(string $endpoint): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL.$endpoint);

            return $this->processResponse($response);
        } catch (\Exception|TransportExceptionInterface $e) {
            throw new \RuntimeException("Erreur lors de la récupération des lancements : {$e->getMessage()}");
        }
    }

    private function processResponse(ResponseInterface $response): array
    {
        return $response->toArray();
    }

    public function getAllLaunches(): array
    {
        return $this->fetchLaunches('/');
    }

    public function getLatestLaunches(): array
    {
        return $this->fetchLaunches('/latest');
    }

    public function getUpcomingLaunches(): array
    {
        return $this->fetchLaunches('/upcoming');
    }

    public function getPastLaunches(): array
    {
        return $this->fetchLaunches('/past');
    }

    public function getLaunchDetails(string $id): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL."/{$id}");

            return $this->processResponse($response);
        } catch (\Exception|TransportExceptionInterface $e) {
            throw new \RuntimeException("Impossible de récupérer les détails du lancement : {$e->getMessage()}");
        }
    }

    public function getCrewMembers(string $id): array
    {
        try {
            $response = $this->httpClient->request('GET', "https://api.spacexdata.com/v4/crew/{$id}");

            return $this->processResponse($response);
        } catch (\Exception|TransportExceptionInterface $e) {
            throw new \RuntimeException("Impossible de récupérer les membres d'équipage : {$e->getMessage()}");
        }
    }
}
