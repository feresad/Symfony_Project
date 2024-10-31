<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function countBook(String $category){
        return $this->createQueryBuilder(alias: 'b')
        ->select('count (b.id)')
        ->where('b.category = :category')
        ->setParameter('category',$category)
        ->getQuery()
        ->getSingleColumnResult();
    }

    public function booksListByAuthors():mixed
{
    return $this->createQueryBuilder('b')
                ->join('b.author', 'a')
                ->orderBy('a.Username', 'ASC')
                ->getQuery()
                ->getResult();
}

public function listBooksPublished(): mixed
{
    return $this->createQueryBuilder('b')
        ->join('b.author', 'a')
        ->where('b.publicationDate < :date')
        ->andWhere(
            '(SELECT COUNT(b2.id) FROM App\Entity\Book b2 WHERE b2.author = a.id) > :minBooks'
        )
        ->setParameter('date', new \DateTime('2023-01-01'))
        ->setParameter('minBooks', 10)
        ->getQuery()
        ->getResult();
}

public function listBookByRef($ref):mixed
{
    return $this->createQueryBuilder('b')
                ->where('b.ref = :ref')
                ->setParameter('ref', $ref)
                ->getQuery()
                ->getResult();
}
public function updateCategory():mixed
{
    $em = $this->getEntityManager();
    return $em->createQuery(
        dql:"update APP\Entity\Book b set b.category = :newCategory where b.category = :oldCategory"
    )
    ->setParameter('oldCategory','Science-Fiction')
    ->setParameter('newCategory','Romance')
    ->getResult();

}
//count book qui a comme category 'Romance' avec dql
public function countBookDQL():mixed
{
    $em = $this->getEntityManager();
    return $em->createQuery(
        dql:"select count(b.id) from APP\Entity\Book b where b.category = 'Romance'"
    )
    ->getSingleScalarResult();
}
//Afficher la liste des livres publiés entre deux dates « 2014-01-01 » et «2018- 12-31 » avec dql
public function listBooksDateDQL():mixed
{
    $em = $this->getEntityManager();
    return $em->createQuery(
        dql:"select b from APP\Entity\Book b where b.publicationDate between '2014-01-01' and '2018-12-31'"
    )
    ->getResult();
}
public function DeleteBookwith0nb():mixed{
    $em =$this->getEntityManager();
    return $em->createQuery(
        dql:"delete from APP\ENTITY\Book b where b.nb = 0"
    )->getResult();
}
}