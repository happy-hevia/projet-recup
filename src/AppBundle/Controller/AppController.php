<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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

        //récupère les anciennes images pour pouvoir les supprimer
        $fichierPicture = $evenement->getPicture();
        $fichierPictureMiniature = $evenement->getPictureMin();

        $form = $this->createForm(EventType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            permet de réinitialiser url de l'image miniature dans le cas où la miniature n'est pas créé comme dans
//            le cas de bug ou pour les gifs
            $evenement->setPictureMin(null);

            //modification
            $evenement->upload();
            $em->persist($evenement);

//            si l'internaute change d'image pour l'événement alors on supprime les anciennes
            if ($fichierPicture != $evenement->getPicture()) {
                if (file_exists(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPicture) && $fichierPicture != null) {
                    unlink(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPicture);
                }
                if (file_exists(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPictureMiniature) && $fichierPictureMiniature != null) {
                    unlink(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPictureMiniature);
                }
            }

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
        $session = $request->getSession();

        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
                        ->add('email', EmailType::class, array('attr' => array('autofocus' => 'autofocus')))
                        ->add('objet', TextType::class)
                        ->add('contenu', TextareaType::class)
                        ->add('submit', SubmitType::class, array('attr' => array('value' => 'valider')))
                        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject($contact->getObjet())
                ->setFrom($contact->getEmail())
                ->setTo('happyhevia@gmail.com')
                ->setBody($contact->getContenu());

            $this->get('mailer')->send($message);

            // set flash messages
            $this->addFlash('ValidationEnvoie', "L'émail a bien été envoyé !");

        }
        return $this->render(':app:contact.html.twig', array(
            'form' => $form->createview()
        ));
    }
}
