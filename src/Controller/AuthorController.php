<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route("/author")]
class AuthorController extends AbstractController
{
    #[Route('/authorindex', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/show/{name}', name: 'show_author' )]
    public function show($name): Response{
        return $this->render('/author/show.html.twig',[
            'name' => $name,
            ]);
    }
    #[Route('/list', name: 'list_authors')]
    public function listAuthors():Response {
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/list.html.twig',[
            'authors'=> $authors
        ]);

    }
  // crée une methode authorDetails pour afficher les details d'un author avec l'id
  #[Route('/details/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        $authors = $this->getAuthors();
        $author = null;

        // Rechercher l'auteur par son ID
        foreach ($authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }
    private function getAuthors(): array
    {
        return [
            ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];
    }

    #[Route('/listDb', name: 'list_db')]
    public function listDb(ManagerRegistry $doctrine): Response{
        $rep = $doctrine->getRepository(Author::class);
        $list = $rep->findAll(); // liaison avec la bd
        return $this->render('author/listdb.html.twig', [
            "list"=> $list,
        ]);
    }
    #[Route('/listDb2', name: 'list_db')]
    public function listDb2(AuthorRepository $repo): Response{
        $list = $repo->findAll(); // liaison avec la bd
        return $this->render('author/listdb2.html.twig', [
            "list"=> $list,
        ]);
    }

    #[Route('/showlist/{id}', name: 'show_detailsdb')]
    public function AfficherDetails($id, AuthorRepository $repo): Response{
        $author = $repo->find($id);
        return $this->render('author/showAuthor2.html.twig', [
            'author' => $author,
        ]);
    }
    // crée une methode pour ajouter un auteur
    #[Route('/addAuthorStatic', name: 'add_author')]
    public function add(ManagerRegistry $doctrine): Response{
        $manager = $doctrine->getManager();
        $author = new Author();
        $author->setUsername('Victor Hugo');
        $author->setEmailAdress('folen@gmail.com');
        $author->setNbBooks(100);
        $manager -> persist($author);//insert
        $manager -> flush();// ajout dans la bd
        return new Response('Author Added');
    }
    #[Route('/form', name: 'form')]
    public function addAuthor(ManagerRegistry $manager, Request $request): Response{
        $em = $manager->getManager();
        $author = new Author();
        //appel du formulaire
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);//recuperer les données du formulaire
        if($form->isSubmitted()){
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('list_db');
        }
        return $this->render('author/form.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/updateAuthor/{id}', name: 'update_author')]
    public function EditAuthor(ManagerRegistry $manager, Request $request,$id,AuthorRepository $repo): Response{
        $em = $manager->getManager();
        $author = $repo->find($id);
        //appel du formulaire
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);//recuperer les données du formulaire
        if($form->isSubmitted()){
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('list_db');
        }
        return $this->render('author/form.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/deleteAuthor/{id}', name: 'delete_author')]
    public function deleteAuthor(ManagerRegistry $manager, Author $author): Response{
        $em = $manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('list_db');
    }
}