<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Evenement;

class AppController extends Controller {
	/**
	 * @route("/")
	 */
	public function accueilAction($_route) {
		return $this->render(':App:accueil.html.twig', array('route'=>$_route));
	}
	
	/**
	 * @route("/evenement")
	 */
	public function evenementAction($_route) {
		
		$em = $this->getDoctrine()->getManager();
		
		$evenements = $em->getRepository('AppBundle:Evenement')->getAllEvents();
		return $this->render(':App:evenement.html.twig', array('route'=>$_route, 'evenements' => $evenements));
	}
	
	/**
	 * @route("evenement/{id}",requirements={ "id" = "^\d+"})
	 */
	public function descriptionAction($id, $_route) {
		$em = $this->getDoctrine()->getManager();
		
		$evenement = $em->getRepository('AppBundle:Evenement')->findOneById(1);
		
		return $this->render(':App:description.html.twig', array (
				'route' => $_route,
				'event' => $evenement
		) );
	}
}