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
use Doctrine\ORM\Query\ResultSetMapping;

class ReservationRepository extends EntityRepository
{
    public function findAllConfirmed()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('r')
            ->from('AppBundle:Reservations', 'r')
            ->join('r.reservation', 'res')
            ->where('res.confirmedTime is not null')
            ->orderBy('r.date')
            ->groupBy('res.id');
        $reservations=$qb->getQuery()->getResult();
        $parent=[];
        foreach($reservations as $res){
            array_push($parent, $res->getReservation());
        }
        return $parent;
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
        $reservations=$qb->getQuery()->getResult();
        $parent=[];
        foreach($reservations as $res){
            array_push($parent, $res->getReservation());
        }
        return $parent;
    }

    public function findDistinctFields($reservation){

        $qb = $this->_em->createQueryBuilder();
        $qb->select('res')
            ->from('AppBundle:Reservations', 'res')
            ->leftJoin('res.reservation', 'r')
            ->join('res.field', 'f')
            ->groupBy('res.field')
            ->where('r.id IN (:id)')
            ->setParameter('id', $reservation);
        $reservation=$qb->getQuery()->getResult();

        return $reservation;
    }

    public function findFutureReservations(){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('res')
            ->from('AppBundle:Reservations', 'res')
            ->leftJoin('res.reservation', 'r')
            ->where('res.date > :date')
            ->andWhere('r.confirmedTime is not null')
            ->groupBy('r.id')
            ->orderBy('res.date')
            ->setParameter('date', date("Y-m-d", time()));
        $reservation=$qb->getQuery()->getResult();

        $parent=[];
        foreach($reservation as $res){
            array_push($parent, $res->getReservation());
        }
        return $parent;
    }

    public function findDistinctTimes($reservation){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('distinct res.date')
            ->from('AppBundle:Reservations', 'res')
            ->leftJoin('res.reservation', 'r')
            ->orderBy('res.date')
            ->where('r.id = :id')
            ->setParameter('id', $reservation);
        $reservation=$qb->getQuery()->getResult();

        return $reservation;
    }
}