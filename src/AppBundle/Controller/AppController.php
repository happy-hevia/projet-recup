<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Evenement;
use AppBundle\Form\Type\EventType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppController extends Controller {
	
	/**
	 * @route("/")
	 */
	public function accueilAction() {
		
		
		return $this->render ( ':App:accueil.html.twig', array (
		) );
	}
	
	/**
	 * @route("/evenement")
	 */
	public function evenementAction() {
		$em = $this->getDoctrine ()->getManager ();
		
		$evenements = $em->getRepository('AppBundle:Evenement' )->getAllEvents ();
		$evenementsCreation = $em->getRepository('AppBundle:Evenement' )->getAllEventsByCreated();

		//		supprime les balises et transforme les caractères spéciaux en caractères html
		foreach( $evenements as $id => $e ){
			$e->setContent(strip_tags(html_entity_decode($e->getContent())));
		}

		return $this->render ( ':App:evenement.html.twig', array (
				'evenements' => $evenements,
				'evenementsCreation' => $evenementsCreation,
		) );
	}
	
	/**
	 * @route("/evenement/{id}",requirements={ "id" = "^\d+"})
	 */
	public function descriptionAction(Evenement $evenement) {
		$em = $this->getDoctrine ()->getManager ();
		$evenements = $em->getRepository('AppBundle:Evenement' )->getAllEvents ();
		$evenementsCreation = $em->getRepository('AppBundle:Evenement' )->getAllEventsByCreated();



			return $this->render ( ':App:description.html.twig', array (
				'evenement' => $evenement,
				'evenements' => $evenements,
				'evenementsCreation' => $evenementsCreation,
			) );
	}
	
	/**
	 * @route("/creation")
	 */
	public function creationAction(Request $request) {
		
		$em = $this->getDoctrine ()->getManager ();
		$evenement = new Evenement ();
		$evenements = $em->getRepository('AppBundle:Evenement' )->getAllEvents ();
		$evenementsCreation = $em->getRepository('AppBundle:Evenement' )->getAllEventsByCreated();
		$form = $this->createForm (EventType::class, $evenement);
		
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$evenement->upload();
			$em->persist($evenement);
			$em->flush();

			return $this->redirectToRoute('app_app_description', array('id' => $evenement->getId()));
		}
		return $this->render ( ':App:creation.html.twig', array (
			'form' => $form->createView(),
				'evenements' => $evenements,
				'evenementsCreation' => $evenementsCreation,
				
		) );
	}
	
	/**
	 * @route("/modification/{id}", requirements={ "id" = "^\d+"})
	 */
	public function modificationAction(Evenement $evenement, Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$evenements = $em->getRepository('AppBundle:Evenement' )->getAllEvents ();
		$evenementsCreation = $em->getRepository('AppBundle:Evenement' )->getAllEventsByCreated();
		$form = $this->createForm (EventType::class, $evenement)
					->add('remove', SubmitType::class, array('attr' => array('label' => 'supprimer')));
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){
			// suppression
			if($form->get('remove')->isClicked()){
				$em->remove($evenement);
				$em->flush();
				
			return $this->redirectToRoute('app_App_accueil');
			}
			
			//modification
			$evenement->upload();
			$em->persist($evenement);
			$em->flush();
			
			return $this->redirectToRoute('app_app_description', array('id' => $id));
		}
		
		return $this->render ( ':App:modification.html.twig', array (
				'evenement' => $evenement,
				'form' => $form->createView (),
				'evenements' => $evenements,
				'evenementsCreation' => $evenementsCreation,
		) );
	}
	
	/**
	 * @route("/login", name="login")
	 */
	public function loginAction(){
		$reponse = new Response();
		$reponse->setStatusCode(Response::HTTP_OK);
		$authenticationUtils = $this->get('security.authentication_utils');
		 
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		 
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();
		 
		return $this->render(
				':app:login.html.twig',
				array(
						// last username entered by the user
						'last_username' => $lastUsername,
						'error'         => $error,
				)
				);
	}
	
	/**
	 * @route("/login_check", name="login_check")
	 */
	public function loginCheckAction(){
	
	}
	
	/**
	 * @route("/logout", name="logout")
	 */
	public function logoutAction(){
	
	}
	
	/**
	 * 
	 */
	public function EventsAction()
	{
		$em = $this->getDoctrine ()->getManager ();
		$evenements = $em->getRepository('AppBundle:Evenement' )->getAllEvents ();
		$evenementsCreation = $em->getRepository('AppBundle:Evenement' )->getAllEventsByCreated();
		
		return $this->render(':element:composant.html.twig', array(
				'evenements' => $evenements,
				'evenementsCreation' => $evenementsCreation,
		)) ;
	}

	/**
	 * @route("/cgu")
	 */
	public function CguAction()
	{


		return $this->render(':app:cgu.html.twig');

	}
}
