<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ListReservationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/list/{time}", name="listReservations", defaults={"time" = "all"})
     */
    public function displayReservationAction($time)
    {
        $reservations=array();
        if($time=="all"){
            $reservations=$this->getDoctrine()->getRepository('AppBundle:Reservation')->findAll();
        }elseif($time=="confirmed"){
            $reservations=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findAllConfirmed();
        }elseif($time=="yesterday"){
            $date=new \DateTime('-1 days');
            $reservations=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findAllWithDate($date->format('Y-m-d'));
        }elseif($time=="today"){
            $date=new \DateTime();
            $reservations=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findAllWithDate($date->format('Y-m-d'));
        }elseif($time=="futureconfirmed"){
            $reservations=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findFutureReservations();
        }

        $distinctfields=[];
        $distinctdates=[];
        foreach ($reservations as $res){
            $distinctfields[$res->getId()]=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findDistinctFields($res);
            $distinctdates[$res->getId()]=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findDistinctTimes($res);
        }

        dump($distinctdates);

        return $this->render(':default:listRes.html.twig',array(
            'reservations' => $reservations,
            'distinctfields' => $distinctfields,
            'distinctdates' => $distinctdates
        ));
    }

    /**
     * @param $reservations
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route (name="makeReservationsTable")
     */
    public function makeReservationsTableAction($reservations){
        return $this->render(':default:reservationsTable.html.twig',array(
            'reservations' => $reservations
        ));
    }


    /**
     * @param $reservation
     * @return string
     */
    public function getUniqueFieldsAction($reservation){
        $reservations=$this->getDoctrine()->getRepository('AppBundle:Reservations')->findBy(array(
            'reservation' => $reservation
        ));
        $fields=[];
        foreach ($reservations as $reservation) {
            array_push($fields, $reservation->getField());
        }
        $fields=array_unique($fields, SORT_REGULAR);
        //dump($fields);
        return $this->render(':default:uniqueFields.html.twig', array(
            'fields' => $fields
        ));
    }
}
