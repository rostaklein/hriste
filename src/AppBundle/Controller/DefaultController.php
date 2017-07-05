<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $fields=$this->getDoctrine()->getRepository('AppBundle:Fields')->findAll();
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'fields' => $fields
        ]);

    }


    /**
     * @Route("/timetable", name="timetable")
     */
    public function getTimetableAction(Request $request){
        $date=$request->get('date');
        $field=$request->get('field');
        $date=new \DateTime($date);
        if(!isset($field)){
            $field=1;
        }

        $confirmedres=$this->getDoctrine()->getManager()->getRepository('AppBundle:Reservation')->findAllConfirmed();

        $confirmedtimes=$this->getDoctrine()->getRepository('AppBundle:Reservations')->findBy(array(
                'reservation' => $confirmedres,
                'date' => $date,
                'field' => $field
            ));

        $disableddates=array();
        foreach ($confirmedtimes as $r){
            $timeFrom=intval($r->getTimefrom()->format('G'));
            $timeTo=intval($r->getTimeto()->format('G'));
            for($i=$timeFrom;$i<$timeTo;$i++){
                $disableddates[$i]=$r->getId();
            }
        }

        $field=$this->getDoctrine()->getRepository('AppBundle:Fields')->find($field);

        $session = $this->get('session');

        $activedatessess = unserialize($session->get('selectedtimes'));
        if(!$activedatessess){$activedatessess=array();};
        $activedates=array();

        foreach ($activedatessess as $active){
            if($active['field']->getId()==$field->getId() && $active['date']==$date){
                $activedates[]=$active['timefrom']->format('G');
            }
        }

        $resInfo=array(
            'fieldname' => $field->getName(),
            'date' => $date
        );

        return $this->render('default/timetable.html.twig', array(
            'disableddates' => $disableddates,
            'activedates' => $activedates,
            'resinfo' => $resInfo
        ));
    }


    /**
     * @Route("/selectedtimes", name="selectedtimes")
     */
    public function getSelectedTimesAction(Request $request){
        $hideClearTimes=$request->get('hideClearTimes');
        $nextStep=$request->get('nextStep');{
            if(!$nextStep){
                $nextStep="step2";
            }
        }

        $session = $this->get('session');

        if($request->get('clear')==true){
            $session->clear();
        }

        $selectedtimes = unserialize($session->get('selectedtimes'));
        if (!$selectedtimes) {
            $selectedtimes = array();
        }

        if($request->isXmlHttpRequest() && $request->get('clear')==false) {
            $date = $request->get('date');
            $field = $request->get('field');
            $timefrom = $request->get('time');
            $date = new \DateTime($date);
            $timefrom = new \DateTime($timefrom);
            $timeto = clone $timefrom;

            //Omezeni datumu z vrchu a zespoda
            $datelimitTop= new \DateTime();
            $datelimitTop->modify('+ 2 months');
            $datelimitBot= new \DateTime();
            $datelimitBot->modify('- 1 days');

            if($date>$datelimitTop || $date<$datelimitBot){
                $this->get('session')->getFlashBag()->add('error', 'Nelze udělat rezervaci na čas, který už byl v minulosti.');
                throw $this->createNotFoundException('Nelze provést operaci.');
            }
            $timeto->modify('+ 1 hour');


            if (!isset($field)) {
                $field = 1;
            }


            /*$field=$this->getDoctrine()->getRepository('AppBundle:Fields')->find($field);
            $user=$this->getDoctrine()->getRepository('AppBundle:Users')->find(1);

            $newreservation=new Reservations();
            $newreservation->setCode('ahoj')->setTimefrom($time)->setTimeto($time)->setDate($date)->setField($field)->setUser($user)->setConfirmed(false);

            $em = $this->getDoctrine()->getManager();

            $em->persist($newreservation);

            $em->flush();*/

            $field = $this->getDoctrine()->getRepository('AppBundle:Fields')->find($field);

            $selectednow = array(
                'date' => $date,
                'timefrom' => $timefrom,
                'timeto' => $timeto,
                'field' => $field
            );

            $alreadythere = array_search($selectednow, $selectedtimes);

            if ($alreadythere === false) {
                $selectedtimes[]=$selectednow;
            } else {
                unset($selectedtimes[$alreadythere]);
            }

            $session->set('selectedtimes', serialize($selectedtimes));
        }

        $selectedtimes=unserialize($session->get('selectedtimes'));

        $uniquefields=array();
        if($selectedtimes){
            foreach ($selectedtimes as $time){
                if(!in_array($time['field'], $uniquefields)){
                    $uniquefields[]=$time['field'];
                }
            }
        }


        return $this->render('default/selectedTimes.html.twig', array(
            'selectedtimes' => $selectedtimes,
            'hideClearTimes' => $hideClearTimes,
            'uniqueFields' => $uniquefields
        ));
    }

    /**
     * @Route ("/prices", name="prices")
     */
    public function pricesAction(){
        return $this->render('default/prices.html.twig');
    }
}
