<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
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
    public function orderListQb():mixed{
        return $this->createQueryBuilder('a')
        ->orderBy("a.username","ASC")
        ->getQuery()
        ->getResult();
    }
    public function orderListDQl():mixed{
       $em= $this->getEntityManager();
       return $em ->createQuery(
        dql:"select * from APP\Entity\Author a orderBy a.username"
       ) ->getResult();
    }
    public function showMoreThan10($nb):mixed{
        return $this->createQueryBuilder('a')
        ->where("a.nbBooks > :nb ")
        ->setParameter('nb',$nb)
        ->getQuery()
        ->getResult();
    }

    public function deleteAuthor():mixed{
        $em = $this->getEntityManager();
        return $em->createQuery(
            dql:"delete from APP\Entity\Author a where a.nbBooks = 0"
        ) ->getResult();
    }
    public function listAuthorByEmail():mixed
    {
        return $this->createQueryBuilder('a')
                    ->orderBy('a.emailAdress', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
    public function findAuthorsByBooksRange($min, $max): array {
        return $this->getEntityManager()
                    ->createQuery('
                        SELECT a 
                        FROM App\Entity\Author a
                        WHERE a.nbBooks BETWEEN :min AND :max
                        ORDER BY a.nbBooks ASC
                    ')
                    ->setParameter('min', $min)
                    ->setParameter('max', $max)
                    ->getResult();
    }
    public function DeleteAuthorwith0nbBooks():mixed{
        $em =$this->getEntityManager();
        return $em->createQuery(
            dql:"delete from APP\ENTITY\Author a where a.nbBooks = 0"
        )->getResult();
    }
    

}
