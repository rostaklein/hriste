<?php

/**
 * Created by PhpStorm.
 * User: rostaklein
 * Date: 03/06/2017
 * Time: 00:05
 */
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Reservations;

class ReservationRepository extends EntityRepository
{
    public function findAllConfirmed()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Reservation')->createQueryBuilder('p')
            ->where('p.confirmedTime is not null')
            ->getQuery()->getResult();
    }

    public function findAllWithDate($date)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('AppBundle:Reservations', 'r')
            ->join('r.reservation', 'res')
            ->where('r.date = :date')
            ->groupBy('res.id')
            ->setParameter('date', $date);
        return $qb->getQuery()->getResult();
    }
}