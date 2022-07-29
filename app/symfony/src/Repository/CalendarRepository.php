<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<Calendar>
 *
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Calendar $entity, bool $flush = true): void
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
    public function remove(Calendar $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findCurrentCalendar($company_id): ?Calendar {
        $actual_date = new DateTime();
        $actual_date = $actual_date->format('Y-m-d');

        return $this->createQueryBuilder('i')
            ->andWhere('i.initial_date < :actual_date OR i.initial_date = :actual_date')
            ->andWhere('i.final_date > :actual_date OR i.initial_date = :actual_date')
            ->andWhere('i.company = :company_id')
            ->setParameter('actual_date', $actual_date)
            ->setParameter('company_id', $company_id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findCalendarByDates($initial_date, $final_date): ?Calendar
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.initial_date < :ini OR i.initial_date = :ini')
            ->andWhere('i.final_date > :fin OR i.final_date = :fin')
            ->setParameter('ini', $initial_date)
            ->setParameter('fin', $final_date)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

//    /**
//     * @return Calendar[] Returns an array of Calendar objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Calendar
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
