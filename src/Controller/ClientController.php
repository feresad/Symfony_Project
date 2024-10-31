<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
    #[Route('/list', name: 'list_db')]
    public function listDb2(ClientRepository $repo): Response{
        $lists = $repo->findAll(); // liaison avec la bd
        return $this->render('client/list.html.twig', [
            "lists"=> $lists,
        ]);
    }
    #[Route('/add', name: 'add_client')]
    public function add(ManagerRegistry $doctrine,Request $request,ClientRepository $repo): Response{
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($repo->findBy(['email' => $client->getEmail()])){
            return new Response('Email existe déjà');
        }
        elseif($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('list_db');
        }
        return $this->render('client/add.html.twig',[
            'form' => $form->createView(),
        ]);
      
    }
}
