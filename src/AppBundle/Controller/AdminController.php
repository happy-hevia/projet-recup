<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Evenement;

/**
 * @route("/admin")
 */
class AdminController extends Controller {
	
	/**
	 * @route("/")
	 */
	public function accueilAction($_route) {
		return $this->render ( ':Admin:accueil.html.twig', array (
				'route' => $_route 
		) );
	}
	
	/**
	 * @route("/evenement")
	 */
	public function evenementAction($_route) {
		$em = $this->getDoctrine()->getManager();
		
		$evenements = $em->getRepository('AppBundle:Evenement')->getAllEvents();
		
		return $this->render ( ':Admin:evenement.html.twig', array (
				'route' => $_route,
				'evenements' => $evenements
		) );
	}
	
	/**
	 * @route("/evenement/{id}",requirements={ "id" = "^\d+"})
	 */
	public function descriptionAction($id, $_route) {
		
		$em = $this->getDoctrine()->getManager();
		
		$evenement = $em->getRepository('AppBundle:Evenement')->findOneById(1);
		
		return $this->render(':Admin:description.html.twig', array (
				'route' => $_route,
				'event' => $evenement
		) );
	}
	
	/**
	 * @route("/creation")
	 */
	public function creationAction($_route) {
		$em = $this->getDoctrine ()->getManager ();
		
		$evenement = new Evenement ();
		$evenement->setAuthor ( 'paul' )->setContent ( 'je suis une légende' )->setDay ( new \DateTime () )->setHour ( new \DateTime () )->setTitle ( 'test' )->setCreated ( new \DateTime () );
		
		$em->persist ( $evenement );
		$em->flush ();
		
		return $this->render ( ':Admin:creation.html.twig', array (
				'route' => $_route 
		) );
	}
	
	/**
	 * @route("/modification/{id}", requirements={ "id" = "^\d+"})
	 */
	public function modificationAction($id, $_route) {
		
		$em = $this->getDoctrine ()->getManager ();
		$evenement = $em->getRepository('AppBundle:Evenement')->findOneById($id);
		
		
		return $this->render ( ':Admin:modification.html.twig', array (
				'route' => $_route,
				'evenement' => $evenement
		) );
	}
}