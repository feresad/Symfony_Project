<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/compte')]
class CompteController extends AbstractController
{
    #[Route('/compte', name: 'app_compte')]
    public function index(): Response
    {
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }
    #[Route('/add', name:'add_Compte')]
    public function addCompte(ManagerRegistry $doctrine,Request $request): Response{
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($compte);
            $em->flush();
            return new Response('Compte Ajoutée');
        }
        return $this->render('compte/form.html.twig',[
            'form' => $form->createView(),
        ]);

      
    }


}
