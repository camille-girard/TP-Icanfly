<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class YouTubeService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function getVideoThumbnail(string $videoUrl): ?string
    {
        // Extraire l'ID de la vidéo depuis l'URL
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
        $videoId = $matches[1] ?? null;

        if (!$videoId) {
            return null;
        }

        // Construire l'URL de l'API YouTube pour récupérer les détails de la vidéo
        $url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$videoId}&key={$this->apiKey}";

        // Appel API
        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();

        // Récupérer l'image de la vidéo
        return $data['items'][0]['snippet']['thumbnails']['maxres']['url'] ?? "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
    }
}
