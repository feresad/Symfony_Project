<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route("/First")]
class FirstController extends AbstractController
{
    #[Route('/index', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => '3A7',
            'first_name' => 'Feres'
        ]);
    }

    #[Route('/redirect', name: 'first_redirect')]
    public function redirectExample():Response {
        return $this->redirectToRoute('app_first');
    }
    #[Route('/show/{name}', name: 'first_show')]
    public function show($name): Response{
        return $this->render('first/show.html.twig',
    [
        'n' => $name,
    ]);
    }
}
