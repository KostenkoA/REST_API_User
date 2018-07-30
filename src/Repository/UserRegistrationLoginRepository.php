<?php

namespace App\Repository;

use App\Entity\UserRegistrationLogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRegistrationLogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRegistrationLogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRegistrationLogin[]    findAll()
 * @method UserRegistrationLogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRegistrationLoginRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRegistrationLogin::class);
    }

    public function findAllUsersByDate($date)
    {
       $date = new \DateTime($date);

        return $this->createQueryBuilder('p')
            ->select('p.userId')
            ->where('p.lastLoginAt <= :date')
           ->setParameter(':date', $date->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;

    }

//    /**
//     * @return UserRegistrationLoginController[] Returns an array of UserRegistrationLoginController objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserRegistrationLoginController
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
