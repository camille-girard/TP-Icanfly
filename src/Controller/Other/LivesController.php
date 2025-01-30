<?php

namespace App\Controller\Other;

use App\Entity\VideoStreaming;
use App\Form\VideoStreamingFormType;
use App\Repository\VideoStreamingRepository;
use App\Service\YouTubeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LivesController extends AbstractController
{
    #[Route('/lives', name: 'page_lives', methods: ['GET'])]
    public function index(VideoStreamingRepository $videoStreamingRepository, YouTubeService $youTubeService): Response
    {
        $videoStreamings = $videoStreamingRepository->findAll();

        foreach ($videoStreamings as $video) {
            $video->thumbnail = $youTubeService->getVideoThumbnail($video->getUrl());
        }

        return $this->render('other/lives.html.twig', [
            'video_streamings' => $videoStreamings,
        ]);
    }

    #[Route('/lives/{id}', name: 'page_live_detail', methods: ['GET'])]
    public function detail(VideoStreaming $videoStreaming): Response
    {
        // Transformation de l'URL YouTube pour éviter les cookies et s'assurer du bon format
        $videoUrl = str_replace('watch?v=', 'embed/', $videoStreaming->getUrl());
        $videoUrl = str_replace('youtube.com', 'youtube-nocookie.com', $videoUrl);

        return $this->render('other/detail-lives.html.twig', [
            'video_streaming' => $videoStreaming,
            'video_url' => $videoUrl,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/lives', name: 'dashboard_lives_index', methods: ['GET'])]
    public function dashboardIndex(VideoStreamingRepository $videoStreamingRepository): Response
    {
        $videoStreamings = $videoStreamingRepository->findAll();

        return $this->render('dashboard/live/index.html.twig', [
            'video_streamings' => $videoStreamings,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/lives/new', name: 'dashboard_lives_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $videoStreaming = new VideoStreaming();
        $form = $this->createForm(VideoStreamingFormType::class, $videoStreaming);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($videoStreaming);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_lives_index');
        }

        return $this->render('dashboard/live/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/lives/{id}/edit', name: 'dashboard_lives_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VideoStreaming $videoStreaming, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VideoStreamingFormType::class, $videoStreaming);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_lives_index');
        }

        return $this->render('dashboard/live/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/lives/{id}/delete', name: 'dashboard_lives_delete', methods: ['POST'])]
    public function delete(Request $request, VideoStreaming $videoStreaming, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$videoStreaming->getId(), $request->request->get('_token'))) {
            $entityManager->remove($videoStreaming);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_lives_index');
    }

    #[IsGranted('ROLE_OPERATOR')]
    #[Route('/dashboard/lives/{id}', name: 'dashboard_lives_show', methods: ['GET'])]
    public function show(VideoStreaming $videoStreaming): Response
    {
        return $this->render('dashboard/live/show.html.twig', [
            'video_streaming' => $videoStreaming,
        ]);
    }
}
