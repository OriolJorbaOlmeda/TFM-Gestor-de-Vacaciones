<?php

namespace App\Repository;

use App\Entity\Petition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Petition>
 *
 * @method Petition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Petition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Petition[]    findAll()
 * @method Petition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Petition::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Petition $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Petition $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findVacationsByUserAndCalendar($user_id, $calendar_id, $pag): array
    {
        $limit = 5;
        $offset = $limit * (intval($pag) - 1);

        return $this->createQueryBuilder('i')
            ->andWhere('i.employee = :user_id')
            ->andWhere('i.calendar = :calendar_id')
            ->andWhere('i.type = :type')
            ->setParameter('user_id', $user_id)
            ->setParameter('calendar_id', $calendar_id)
            ->setParameter('type', "VACATION")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAbsencesByUserAndCalendar($user_id, $calendar_id, $pag): array
    {
        $limit = 5;
        $offset = $limit * (intval($pag) - 1);

        return $this->createQueryBuilder('i')
            ->andWhere('i.employee = :user_id')
            ->andWhere('i.calendar = :calendar_id')
            ->andWhere('i.type = :type')
            ->setParameter('user_id', $user_id)
            ->setParameter('calendar_id', $calendar_id)
            ->setParameter('type', "ABSENCE")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return Petition[] Returns an array of Petition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Petition
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
