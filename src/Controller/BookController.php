<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    private $bookrepository;
    private $entityManager;

    public function __construct(BookRepository $bookRepositoryparam , EntityManagerInterface $entityManager )
    {
        $this->bookrepository = $bookRepositoryparam;
        $this->entityManager = $entityManager;
    }
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/listBook', name: 'app_listBook')]
    public function listBook(): Response
    {
        $list = $this->bookrepository->findAll();
        return $this->render('book/listBook.html.twig', [
            'books' => $list,
        ]);
    }

    #[Route('/addBooks', name: 'app_addBooks')]
    public function addBooks(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($book);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_listBook');
        }
        return $this->render('book/addBooks.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deleteBooks/{id}', name: 'app_deleteBooks')]
    public function deleteBooks(Book $book): Response
    {
        if ($book)
        {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_listBook');
    }



    #[Route('/updateBook/{id}', name: 'app_updateBook')]
    public function updateBook(Request $request , Book $book): Response
    {
        if ($book)
        {
            $form = $this->createForm(BookType::class, $book);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $this->entityManager->flush();
                return $this->redirectToRoute('app_listBook');
            }
        }

        return $this->render('book/updateBook.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    
}
