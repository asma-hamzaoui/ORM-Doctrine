<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    // Méthode pour récupérer tous les auteurs
    public function findAllAuthors(): array
    {
        return $this->findAll();
    }

    // Méthode pour récupérer un auteur à partir de son id

   public function findAuthorById(int $id): ?Author
{
   return $this->find($id); // Utilise la méthode find de Doctrine
}

public function findAuthorByEmail($val): ?array
{
    return $this->createQueryBuilder( alias: 'e')
       ->where( predicates: 'e.email LIKE :val')
       ->setParameter(key: 'val', value: '%' . $val . '%')
       ->getQuery()
       ->getResult();
}


//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
