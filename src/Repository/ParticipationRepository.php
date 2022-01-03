<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Participation;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{

    const EMPLOYEE = 'employee';
    const EVENTNAME = 'eventName'; 
    const EVENTDATE = 'eventDate'; 

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }



    public function findFiltered(array $data)
    {

        $q = $this->createQueryBuilder('p')
            // ->andWhere('p.exampleField = :val')
            // ->setParameter('val', $value)
            // ->orderBy('p.id', 'ASC')
            // ->setMaxResults(10)
            // ->getQuery()
            // ->getResult()
        ;
        if ($data[$this::EMPLOYEE]) {
            $q->innerJoin(User::class, 'u')
            ->andWhere('p.attendant = :user')
            ->setParameter('user', $data[$this::EMPLOYEE])
            ;
        }
        if ($data[$this::EVENTNAME] || $data[$this::EVENTDATE]) {
            $name = ($data[$this::EVENTNAME])->getName();
            $q->innerJoin(Event::class, 'e', 'with', 'e.id = p.event')
            ;
        
            if ($data[$this::EVENTNAME]) {
                $name = ($data[$this::EVENTNAME])->getName();
                $q->andWhere('e.name = :name')
                ->setParameter('name', $name)
                ;
            
            }
            if ($data[$this::EVENTDATE]) {
                $date = ($data[$this::EVENTDATE])->getDate();
                $q->andWhere('e.date = :date')
                ->setParameter('date', $date)
                ;
            
            }
        }
        return $q
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Participation[] Returns an array of Participation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
