<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Evenement;
use AppBundle\Form\Type\EventType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppController extends Controller {
	
	/**
	 * @route("/")
	 */
	public function accueilAction($_route) {
		$em = $this->getDoctrine ()->getManager ();
		$evenements = $em->getRepository ( 'AppBundle:Evenement' )->getAllEvents ();
		
		return $this->render ( ':App:accueil.html.twig', array (
				'route' => $_route,
				'evenements' => $evenements,
		) );
	}
	
	/**
	 * @route("/evenement")
	 */
	public function evenementAction($_route) {
		$em = $this->getDoctrine ()->getManager ();
		
		$evenements = $em->getRepository ( 'AppBundle:Evenement' )->getAllEvents ();
		
		return $this->render ( ':App:evenement.html.twig', array (
				'route' => $_route,
				'evenements' => $evenements 
		) );
	}
	
	/**
	 * @route("/evenement/{id}",requirements={ "id" = "^\d+"})
	 */
	public function descriptionAction($id, $_route) {
		$em = $this->getDoctrine ()->getManager ();
		
		$evenement = $em->getRepository ( 'AppBundle:Evenement' )->findOneById ( $id );
		
		if (!$evenement) {
			throw $this->createNotFoundException("l'evenement est introuvable");
		}
		
		return $this->render ( ':App:description.html.twig', array (
				'route' => $_route,
				'evenement' => $evenement 
		) );
	}
	
	/**
	 * @route("/creation")
	 */
	public function creationAction($_route, Request $request) {
		
		$em = $this->getDoctrine ()->getManager ();
		$evenement = new Evenement ();
		$form = $this->createForm (EventType::class, $evenement);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			
			$evenement->upload();
			
			$em->persist($evenement);
			$em->flush();
			
			return $this->redirectToRoute('app_app_description', array('id' => $evenement->getId()));
		}
		return $this->render ( ':App:creation.html.twig', array (
				'route' => $_route,
				'form' => $form->createView(),
		) );
	}
	
	/**
	 * @route("/modification/{id}", requirements={ "id" = "^\d+"})
	 */
	public function modificationAction($id, $_route, Request $request) {
		$em = $this->getDoctrine ()->getManager ();
		$evenement = $em->getRepository ( 'AppBundle:Evenement' )->findOneById ( $id );
		

		if (!$evenement) {
			throw $this->createNotFoundException("l'evenement est introuvable");
		}
		
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
			$em->persist($evenement);
			$em->flush();
			
			return $this->redirectToRoute('app_app_description', array('id' => $id));
		}
		
		return $this->render ( ':App:modification.html.twig', array (
				'route' => $_route,
				'evenement' => $evenement,
				'form' => $form->createView () 
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
}