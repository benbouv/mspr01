<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserPrId($idvalue): array
    {
         return $this->createQueryBuilder('p')
            ->andWhere('p.userWritingMessage = :val')
            ->setParameter('val', $idvalue)
            ->getQuery()
            ->getResult()
         ;
    }

    public function findByUserOtherId($idvalue): array
    {
         return $this->createQueryBuilder('p')
            ->andWhere('p.userReceivingMessage = :val')
            ->setParameter('val', $idvalue)
            ->getQuery()
            ->getResult()
         ;
    }

    public function findByUserBotanistId($idvalue): array
    {
         return $this->createQueryBuilder('p')
            ->andWhere('p.userAdviseMessage = :val')
            ->setParameter('val', $idvalue)
            ->getQuery()
            ->getResult()
         ;
    }
    public function findByAllByIdPlante($idvalue): array
    {
         return $this->createQueryBuilder('p')
            ->andWhere('p.plantInformedByMessage = :val')
            ->setParameter('val', $idvalue)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
         ;
    }

    // public function findByAllByIdPlante($idvalue,$idplante): array
    // {
    //      return $this->createQueryBuilder('p')
    //         ->andWhere('p.userWritingMessage = :val')
    //         ->andWhere('p.userAdviseMessage = :val')
    //         ->andWhere('p.userReceivingMessage = :val')
    //         ->andWhere('p.plantInformedByMessage = :valplante')
    //         ->setParameters(array('val'=> $idvalue, 'valplante' => $idplante))ATTENTION AU S
    //         ->orderBy('p.date', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //      ;
    // }

//    /**
//     * @return Message[] Returns an array of Message objects
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

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
