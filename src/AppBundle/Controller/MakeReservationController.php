<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservation;
use AppBundle\Entity\Reservations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MakeReservationController extends Controller
{
    /**
     * @Route("/submitreservation", name="makereservation")
     */
    public function makeReservationAction()
    {
        // kontrola přihlášení uživatele
        if(!$this->getUser()){
            $this->get('session')->getFlashBag()->add('error', 'Pro vytvoření rezervace je potřeba se přihlásit');
            return $this->redirect($this->generateUrl('step2'));

        }

        // kontrola zvolených časů k uložení
        $selectedtimes=unserialize($this->get('session')->get('selectedtimes'));
        if(!$selectedtimes){
            $this->get('session')->getFlashBag()->add('error', 'Nebyl vybrán čas k rezervaci.');
            return $this->redirect($this->generateUrl('homepage'));
        }

        $selectedtimes=unserialize($this->get('session')->get('selectedtimes'));

        //generování unikátního kódu pro tuto rezervaci

        $now= new \DateTime();
        $resCode="MC".$now->format('njs');

        while($this->getDoctrine()->getRepository('AppBundle:Reservation')->findOneBy(array('code' => $resCode))){
            $now= new \DateTime();
            $resCode="MC".$now->format('njs');
        }

        //vytvoření parent rezervace
        $em = $this->getDoctrine()->getManager();
        $parentreservation = new Reservation();

        $confirmToken=md5($resCode);

        $parentreservation->setCode($resCode)->setCreatedTime($now)->setUser($this->getUser())->setConfirmToken($confirmToken);

        if($this->isGranted('ROLE_ADMIN')){
            $now= new \DateTime();
            $parentreservation->setConfirmedTime($now);
        }else{
            //odeslání confirm emailu
            $message = new \Swift_Message('Potvrzení rezervace '.$resCode);
            $message
                ->setFrom('moravacamp@mohelnickesluzby.cz')
                ->setTo(['moravacamp@mohelnickesluzby.cz', $this->getUser()->getEmail()])
                //->setTo(['rostaklein@gmail.com'])
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        ':emails:confirmEmail.html.twig',
                        array(
                            'confirmToken' => $confirmToken,
                            'code' => $resCode
                        )
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);
        }

        $em->persist($parentreservation);

        //přiřazení jednotlivých časových oken k parentu
        foreach ($selectedtimes as $res){
            $field=$this->getDoctrine()->getRepository('AppBundle:Fields')->find($res['field']);

            $confirmedres=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findAllConfirmed();
            //kontrola, zda už neexistuje v db rezervace na tento čas
            $exists=$this->getDoctrine()->getRepository('AppBundle:Reservations')->findBy(array(
                'date' => $res['date'],
                'timefrom' => $res['timefrom'],
                'timeto' => $res['timeto'],
                'field' => $field,
                'reservation' => $confirmedres
            ));

            if($exists){
                $this->get('session')->getFlashBag()->add('error', 'Někdo byl rychlejší a rezervaci založil před vámi. Prosíme, vyberte si jiný čas.');
                return $this->redirect($this->generateUrl('homepage'));
            }else{
                $newreservation=new Reservations();
                $newreservation->setTimefrom($res['timefrom'])->setTimeto($res['timeto'])->setDate($res['date'])->setField($field)->setReservation($parentreservation);
                $em->persist($newreservation);
            }


        }

        //vyprázdnění session
        $this->get('session')->set('selectedtimes', '');

        $em->flush();


        return $this->redirect($this->generateUrl('reservationDetail',array(
            'code' => $resCode
        )));

    }
    /**
     * @param $code, $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reservation/{code}/confirm/{token}", name="resConfirmation")
     */
    public function resConfirmationAction($code, $token)
    {

        $reservation=$this->getDoctrine()->getRepository('AppBundle:Reservation')->findOneBy(array(
            'code' => $code,
            'confirmToken' => $token
        ));

        if(!$reservation){
            $this->get('session')->getFlashBag()->add('error', 'Potvrzovací kód je nesprávný');
            return $this->redirect($this->generateUrl('reservationDetail', array(
                'code' => $code
            )));
        }else{
            $this->get('session')->getFlashBag()->add('succ', 'Rezervace úspěšně potvrzena');
            $now= new \DateTime();
            $em = $this->getDoctrine()->getManager();
            $reservation->setConfirmedTime($now);
            $em->persist($reservation);
            $em->flush();
        }

        return $this->render(':default:reservationDetail.html.twig',array(
            'res' => $reservation,
            'code' => $code
        ));
    }


    /**
     * @param $code
     * @param Request $request
     * @Route("/reservation/{code}/changename", name="resChangeName")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function resChangeNameAction($code, Request $request){
        $name=$request->get("name");
        $res = $this->getDoctrine()->getRepository('AppBundle:Reservation')->findOneBy(['code' => $code]);
        $res->setOnlyName($name);

        $em = $this->getDoctrine()->getManager();
        $em->persist($res);
        $em->flush();

        return $this->redirect($this->generateUrl('reservationDetail',array(
            'code' => $code
        )));
    }
}
