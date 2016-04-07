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

class AppController extends Controller
{

    /**
     * @route("/")
     */
    public function accueilAction()
    {


        return $this->render(':app:accueil.html.twig', array());
    }

    /**
     * @route("/evenement")
     */
    public function evenementAction()
    {
        $em = $this->getDoctrine()->getManager();

        $nombreTotalEvenements = $em->getRepository('AppBundle:Evenement')->getNumberEvents();
        $evenements = $em->getRepository('AppBundle:Evenement')->getAllEvents();
        $evenementsCreation = $em->getRepository('AppBundle:Evenement')->getSeveralEventsByCreated();

        //		supprime les balises et transforme les caractères spéciaux en caractères html
        foreach ($evenements as $id => $e) {
            $e->setContent(strip_tags(html_entity_decode($e->getContent())));
        }

        return $this->render(':app:evenement.html.twig', array(
            'evenements' => $evenements,
            'evenementsCreation' => $evenementsCreation,
            'nombreTotalEvenements' => $nombreTotalEvenements
        ));
    }

    /**
     * @route("/evenement/{id}",requirements={ "id" = "^\d+"})
     * @param Evenement $evenement
     * @return Response
     */
    public function descriptionAction(Evenement $evenement)
    {

        return $this->render(':app:description.html.twig', array(
            'evenement' => $evenement,
        ));
    }

    /**
     * @route("/creation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function creationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $evenement = new Evenement ();
        $form = $this->createForm(EventType::class, $evenement);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->upload();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('app_app_description', array('id' => $evenement->getId()));
        }
        return $this->render(':app:creation.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    /**
     * @route("/modification/{id}", requirements={ "id" = "^\d+"})
     * @param Evenement $evenement
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function modificationAction(Evenement $evenement, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(EventType::class, $evenement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //modification
            $evenement->upload();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('app_app_description', array('id' => $id));
        }

        return $this->render(':app:modification.html.twig', array(
            'evenement' => $evenement,
            'form' => $form->createView(),
        ));
    }

    /**
     * @route("/login", name="login")
     */
    public function loginAction()
    {
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
                'error' => $error,
            )
        );
    }

    /**
     * @route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * Récupère les données pour afficher le calendrier dans le sidebar
     */
    public function EventsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $evenements = $em->getRepository('AppBundle:Evenement')->getAllEvents();

        return $this->render(':element:composant.html.twig', array(
            'evenements' => $evenements,
        ));
    }

    /**
     * @route("/cgu")
     */
    public function CguAction()
    {
        return $this->render(':app:cgu.html.twig');
    }

    /**
     * @route("/supprimerEvenement/{id}", name ="supprimerEvenement")
     * @param Evenement $evenement
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function SupprimerEvenementAction(Evenement $evenement)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($evenement);
        $em->flush();


        return $this->redirectToRoute('app_app_evenement');
    }

    /**
     * @route("/recupererPlusEvenements/{nombreEvenementsPresents}", requirements={ "nombreEvenementsPresents" = "^\d+"})
     *
     * récupère plus d'événements dans la page d'événments grâce à une requête ajax
     */
    public function RecupererPlusEvenementsAction($nombreEvenementsPresents)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $nouveauxEvenements = $em->getRepository('AppBundle:Evenement')->getSeveralEventsByCreated($nombreEvenementsPresents);

        return $this->render(':element:evenement.html.twig', array(
            'evenementsCreation' => $nouveauxEvenements
        ));

    }
}
