<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/show/{service}',name: 'service_show')]
    public function show($service): Response
    {
        return $this->render('service/show.html.twig', [
            'controller_name' => 'ServiceController',
            'service' => $service,
        ]);
    }

    #[Route('/goToIndex', name: 'service_go_to_index')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('app_first');
    }
}
