<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MicroPost>
 *
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

/**
 * Trova tutte le entità Post con i relativi commenti.
 *
 * @return array Un array contenente tutte le entità Post con i relativi commenti.
 */
public function findAllWithComments(): array {
    // Crea una query builder per l'entità 'Post' con alias 'p'
    return $this->createQueryBuilder('p')
        // Esegue un join con l'entità 'Comment' utilizzando l'alias 'c'
        ->leftJoin('p.comments', 'c')
        // Ordina i risultati in base alla data di creazione della post in ordine decrescente
        ->orderBy('p.datetime', 'DESC')
        // Esegue la query e restituisce il risultato come un array
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return MicroPost[] Returns an array of MicroPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MicroPost
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
