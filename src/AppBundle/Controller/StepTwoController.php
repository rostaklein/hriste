<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StepTwoController extends Controller
{
    /**
     * @Route("/checkuser", name="step2")
     */
    public function stepTwoAction(Request $request)
    {
        $selectedtimes=unserialize($this->get('session')->get('selectedtimes'));
        if(!$selectedtimes){
            //pokud není vybrán žádný čas, redirect na home pro zvolení nového
            $this->get('session')->getFlashBag()->add('error', 'Nevybrali jste žádný čas k rezervaci. Zvolte si jej prosím zde.');
            return $this->redirect($this->generateUrl('homepage'));
        }

        $this->get('session')->set('step2complete', false);

        $notset=array();
        if($this->getUser()){
            //uživatel je přihlášen, kontrola validního emailu a telefonu
            $re = '/^[+]?[0-9. -]{9,}$/';
            preg_match_all($re, $this->getUser()->getPhone(), $notset['validphone'], PREG_SET_ORDER, 0);
            $notset['validemail']=filter_var($this->getUser()->getEmail(), FILTER_VALIDATE_EMAIL);
            if($notset['validemail'] && $notset['validphone']){
                $this->get('session')->set('step2complete', true);
            }
        }


        return $this->render('default/step2.html.twig', array(
            'notset' => $notset,
            'continue' => $this->get('session')->get('step2complete')
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/checkuser/mandatory", name="addPhoneEmail")
     * @Method("POST")
     */
    public function userChangesAction(Request $request)
    {
        //form handling k změně uživ údajů a jejich validace

        $user = $this->getUser();
        $currentuser = $this->getDoctrine()->getRepository('AppBundle:Users')->find($user->getId());


        if($request->get('email')){
            $validemail=filter_var($request->get('email'), FILTER_VALIDATE_EMAIL);
            if($validemail){
                $currentuser->setEmail($request->get('email'));
                $this->get('session')->getFlashBag()->add('succ', 'Email úspěšně nastaven.');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Email byl zadán ve špatném formátu.');
            }

        }
        if($request->get('phone')){
            $re = '/^[+]?[0-9. -]{9,}$/';
            preg_match_all($re, $request->get('phone'), $validphone, PREG_SET_ORDER, 0);
            if($validphone){
                $currentuser->setPhone($request->get('phone'));
                $this->get('session')->getFlashBag()->add('succ', 'Telefon úspěšně nastaven.');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Telefonní číslo bylo zadán ve špatném formátu.');
            }
        }
        if($request->get('fname') && $request->get('lname')){
            if(strlen($request->get('fname'))>=3 && strlen($request->get('lname'))>=3){
                $currentuser->setFname($request->get('fname'));
                $currentuser->setLname($request->get('lname'));
                $this->get('session')->getFlashBag()->add('succ', 'Jméno a příjmené úspěšně nastaveno.');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'Není vaše jméno a příjmení příliš krátké?');
            }
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($currentuser);
        $em->flush();

        return $this->forward('AppBundle:StepTwo:stepTwo');
    }
}
