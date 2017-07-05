<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservation;
use AppBundle\Entity\Reservations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CancelReservationController extends Controller
{
 /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reservation/{code}/cancel", name="resCancel")
     */
    public function resCancelAction($code)
    {

        $reservation=$this->getDoctrine()->getRepository('AppBundle:Reservation')->findOneBy(array(
            'code' => $code
        ));

        if(!$reservation){
            $this->get('session')->getFlashBag()->add('error', 'Rezervace s kódem '.$code.' nebyla nalezena.');
            return $this->redirect($this->generateUrl('homepage'));
        }else{
            $this->get('session')->getFlashBag()->add('succ', 'Rezervace zrušena.');
            $em = $this->getDoctrine()->getManager();
            $reservation->setConfirmedTime(null);
            $reservation->setConfirmToken(null);
            $em->persist($reservation);
            $em->flush();
        }

        return $this->render(':default:reservationDetail.html.twig',array(
            'res' => $reservation,
            'code' => $code
        ));
    }
}
