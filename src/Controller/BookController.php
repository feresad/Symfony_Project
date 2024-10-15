<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/list', name: 'list_db')]
    public function listDb2(BookRepository $repo): Response{
        $list = $repo->findAll(); // liaison avec la bd
        return $this->render('book/list.html.twig', [
            "list"=> $list,
        ]);
    }
    #[Route('/show/{id}', name: 'show_book' )]
    public function show($id, BookRepository $repo): Response{
        $book = $repo->find($id);
        return $this->render('/book/show.html.twig',[
            'book' => $book,
            ]);
    }
    //add 
    #[Route('/add', name: 'add_book')]
    public function add(ManagerRegistry $doctrine,Request $request): Response{
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('list_db');
        }
        return $this->render('book/add.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    //edit
    #[Route('/edit/{id}', name: 'edit_book')]
    public function edit($id, BookRepository $repo, Request $request, ManagerRegistry $doctrine): Response{
        $book = $repo->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('list_db');
        }
        return $this->render('book/add.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    //delete
    #[Route('/delete/{id}', name: 'delete_book')]
    public function delete($id, BookRepository $repo, ManagerRegistry $doctrine): Response{
        $book = $repo->find($id);
        $em = $doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('list_db');
    }

}
