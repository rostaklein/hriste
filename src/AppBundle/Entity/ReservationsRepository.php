<?php

/**
 * Created by PhpStorm.
 * User: rostaklein
 * Date: 03/06/2017
 * Time: 00:05
 */
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReservationsRepository extends EntityRepository
{
    public function findAllConfirmed()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Reservations')->createQueryBuilder('p')
            ->join('p.Field', 'f', 'WITH', 'p.Field = ?1', 'f.id')->where('f.confirmedTime is not null')->getQuery()->getResult();
    }
}