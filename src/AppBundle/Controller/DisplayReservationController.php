<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DisplayReservationController extends Controller
{
    /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayReservationDetailAction($code)
    {

        $reservation=$this->getDoctrine()->getRepository('AppBundle:Reservation')->findBy(array(
            'code' => $code
        ));


        if(!$reservation){
            $this->get('session')->getFlashBag()->add('error', 'Rezervace s kódem '.$code.' neexistuje');
            return $this->redirect($this->generateUrl('homepage'));
        }

        $selectedtimes=$this->getDoctrine()->getRepository('AppBundle:Reservations')->findBy(array(
            'reservation' => $reservation
        ));

        $uniquefields=array();
        if($selectedtimes){
            foreach ($selectedtimes as $time){
                if(!in_array($time->getField(), $uniquefields)){
                    $uniquefields[]=$time->getField();
                }
            }
        }


        return $this->render(':default:selectedTimes.html.twig',array(
            'selectedtimes' => $selectedtimes,
            'hideClearTimes' => true,
            'uniqueFields' => $uniquefields
        ));
    }

    /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reservation/{code}", name="reservationDetail")
     */
    public function displayReservationAction($code)
    {
        $reservation=$this->getDoctrine()->getRepository('AppBundle:Reservation')->findOneBy(array(
            'code' => $code
        ));

        if(!$reservation){
            $this->get('session')->getFlashBag()->add('error', 'Rezervace s kódem '.$code.' neexistuje');
            return $this->redirect($this->generateUrl('findReservation'));
        }

        return $this->render(':default:reservationDetail.html.twig',array(
            'res' => $reservation,
            'code' => $code
        ));
    }

    /**
     * * @Route("/findreservation", name="findReservation")
     */
    public function findReservationAction(){
            $reservations = $this->getDoctrine()->getRepository('AppBundle:Reservation')->findBy(array(
                'user' => $this->getUser()
            ));
            return $this->render(':default:findReservation.html.twig', array(
                'reservations' => $reservations
            ));
    }

    /**
     * @param Request $request
     * @Route("/findResForm", name="findReservationForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findReservationFormAction(Request $request){
        return $this->redirect($this->generateUrl('reservationDetail', array(
            'code' => $request->get('code')
        )));
    }

}
