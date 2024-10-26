<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Author;
use App\Form\AuthorType;

#[Route('/author')]
class AuthorController extends AbstractController
{
    // Propriété de la classe pour stocker l'instance de AuthorRepository
    private $authorRepo;
    private $entityManager;



    // Constructeur qui prend une instance de AuthorRepository
    public function __construct(AuthorRepository $authorRepositoryParam,EntityManagerInterface $entityManagerParam)
    {
        // Assignation de l'instance à la propriété
        $this->authorRepo = $authorRepositoryParam; 
        $this->entityManager=$entityManagerParam;
}

    #[Route('/author', name: 'app_author', methods:['GET'])]
    public function index(): Response
    {

        return $this->render('author/index.html.twig');
    }

    #[Route('/showAuthor/{name}', name: 'app_showAuthor', defaults:['name'=>'victor hugo'], methods:['GET'])]
    public function showAuthor($name): Response
    {
        return $this->render('author/showAuthor.html.twig', [
            'name' => $name
        ]);
    }

    #[Route('/authorList', name: 'app_authorList', methods:['GET'])]
    public function AuthorList(): Response
    {
        // Récupérer la liste des auteurs
        $authors = $this->authorRepo->findAllAuthors(); 

        // Rendre la vue avec la liste des auteurs
        return $this->render('author/authorList.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'author_details', methods: ['GET'])]
    public function authorDetails(int $id): Response
    {
        // Récupérer l'auteur en fonction de l'ID en utilisant le repository
        $author = $this->authorRepo->findAuthorById($id); 

        // Rendre la vue avec les détails de l'auteur
        return $this->render('author/details.html.twig', [
            'author' => $author,
        ]);
    }

//     #[Route('/addAuthorStatic', name: 'add_author', methods: ['GET'])]
//     public function addAuthorStatic():Response{
//     $author=new Author();
//     $author->setUsername('Asma');
//     $author->setEmail('asma.hamzaoui@esprit.tn');
//     $author->setPicture('ggg');
//     $author->setNb_Books(400);

//     $this->entityManager->persist($author);
//     $this->entityManager->flush();

//     return $this->redirectToRoute('app_authorList');

//    }

   #[Route('/author/add', name: 'book.add')]
   public function addAuthor(EntityManagerInterface $em, Request $request): Response
   {
       $book = new Author();
       $form = $this->CreateForm(AuthorType::class, $book);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($book);
           $em->flush();
           return $this->redirectToRoute('books');
       }
       return $this->render('author/addAuthor.html.twig', [
           'book' => $book,
           'form' => $form
       ]);
   }

   #[Route('/delete/{id}', name: 'delete', methods: ['GET','DELETE'])]
   public function delete_Author(Author $author):Response{
    if ($author) {
        $this->entityManager->remove($author);
        $this->entityManager->flush();
    }
        return $this->redirectToRoute('app_authorList');
   }


    #[Route('/updateAuthor/{id}', name: 'update_author', methods: ['GET'])]
    public function updateAuthor(Author $author):Response{
        if($author)
        {
            $author->setUsername('Mahdi');
            $author->setEmail('Mahdi@esprit.tn');
            $author->setPicture('sdkhbk.png');
            $author->setNb_Books(100);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_authorList');
    }
}
