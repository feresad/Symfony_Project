<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\Environment\Console;
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
    #[Route('/list', name: 'list_database')]
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
    #[Route('/add', name: 'add_book')]
    public function add(ManagerRegistry $doctrine,Request $request): Response{
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('list_database');
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
            return $this->redirectToRoute('list_database');
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
        return $this->redirectToRoute('list_database');
    }

    #[Route('/count', name: 'count_book')]
    public function categoryBook(BookRepository $repo):Response{
        $count = $repo ->countBook("Mystery");
        return $this->render('book/count.html.twig',[
            'count' => $count,
        ]);
    }

    #[Route('/listByAuthors', name: 'list_by_authors')]
    public function listByAuthors(BookRepository $repo):Response{
        $list = $repo->booksListByAuthors();
        return $this->render('book/listBookByAuthor.html.twig',[
            "list" => $list,
        ]);
    }

    #[Route('/listPublished', name: 'list_published')]
    public function listPublished(BookRepository $repo): Response
    {
        $list = $repo->listBooksPublished();
        
        // Vérification du contenu de la liste
        if (empty($list)) {
            return new Response('Aucun livre correspondant trouvé.');
        }
        
        return $this->render('book/listPublished.html.twig', [
            'list' => $list,
        ]);
    }
#[Route('/listByRef', name:"author_ref")]
public function listByRef(ManagerRegistry $doctrine,Request $request):Response{
    $repo = $doctrine->getRepository(Book::class);
    $ref = $request->get("ref");
    $list = $repo->listBookByref($ref);
    return $this->render('book/listeBookByref.html.twig',[
        "list" => $list,
    ]);
}
    #[Route('/updateBookCategory', name: 'update_book')]
    public function updateBookCategory(BookRepository $repo):Response{
        $repo->updateCategory();
        return new Response('Catégorie mise à jour');
    }
    #[Route('/countBookDQL', name: 'count_book_dql')]
    public function countBookDQL(BookRepository $repo):Response{
        $count = $repo->countBookDQL();
        return $this->render('book/count.html.twig',[
            'count' => $count,
        ]);
    }
    #[Route('/ListeBookDate', name: 'liste_book_date')]
    public function listeBookDate(BookRepository $repo):Response{
        $list = $repo->listBooksDateDQL();
        return $this->render('book/listBookDate.html.twig',[
            'list' => $list,
        ]);
    }
}
