<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Evenement;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Template(":app:creation.html.twig")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function creationAction(Request $request)
    {
        $gestionFormulaire = $this->get('app_bundle.app.gestion_formulaire');

       if($gestionFormulaire->gereFormulaireCreation($request) === true){
           $evenement = $gestionFormulaire->getEvenement();
           return $this->redirectToRoute('app_app_description', array('id' => $evenement->getId()));
       };

        return array('form' => $gestionFormulaire->gereFormulaireCreation($request)->createView());
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
        $gestionFormulaire = $this->get('app_bundle.app.gestion_formulaire');
        $gereFormulaireModification = $gestionFormulaire->gereFormulaireModification($request, $evenement);

        if($gereFormulaireModification === true){
            return $this->redirectToRoute('app_app_description', array('id' => $id));
        };


        return $this->render(':app:modification.html.twig', array(
            'evenement' => $evenement,
            'form' => $gereFormulaireModification->createView(),
        ));
    }

    /**
     * @route("/login", name="login")
     */
    public function loginAction()
    {
        return $this->render(
            ':app:login.html.twig',
            $this->get('app_bundle.app.gestion_formulaire')->gereFormulaireConnexion()
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

        //		supprime les balises et transforme les caractères spéciaux en caractères html
        foreach ($nouveauxEvenements as $id => $e) {
            $e->setContent(strip_tags(html_entity_decode($e->getContent())));
        }

        return $this->render(':element:evenement.html.twig', array(
            'evenementsCreation' => $nouveauxEvenements
        ));

    }

    /**
     * @route("/contact")
     * @param Request $request
     * @return Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->get('app_bundle.app.gestion_formulaire')->gereFormulaireContact($request);
        return $this->render(':app:contact.html.twig', $form
            );
    }
}
