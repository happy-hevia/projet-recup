<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 19/10/2016
 * Time: 14:45
 */

namespace AppBundle\Services;


use AppBundle\Entity\Contact;
use AppBundle\Entity\Evenement;
use AppBundle\Form\Type\EventType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class GestionFormulaire
{
    private $entityManager;
    private $formFactory;
    private $authenticationUtils;
    private $mailer;
    private $form;
    private $evenement;
    private $session;

    public function __construct(EntityManager $entityManager, FormFactory $formFactory, $authenticationUtils, $mailer, $session)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->authenticationUtils = $authenticationUtils;
        $this->mailer = $mailer;
        $this->session = $session;

    }

    /**
     * @param $request
     * @return bool
     * Créer le formulaire,  traite de le formulaire si celui ci est envoyé par l'internaute
     * retourne true si le formuaire est envoyé
     * retourne le formulaire si le formulaire n'est pas envoyé
     */
    public function gereFormulaireCreation($request)
    {
        $this->evenement = new Evenement();
        $this->form = $this->formFactory->create(EventType::class, $this->evenement);

        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->evenement->upload();
            $this->entityManager->persist($this->evenement);
            $this->entityManager->flush();
            return true;
        }
        return $this->form;
    }

    /**
     * @param $request
     * @param $evenement
     * @return bool
     * Créer le formulaire,  traite de le formulaire si celui ci est envoyé par l'internaute
     * retourne true si le formuaire est envoyé
     * retourne le formulaire si le formulaire n'est pas envoyé
     */
    public function gereFormulaireModification($request, $evenement)
    {
        //récupère les anciennes images pour pouvoir les supprimer
        $fichierPicture = $evenement->getPicture();
        $fichierPictureMiniature = $evenement->getPictureMin();

        $form = $this->formFactory->create(EventType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            permet de réinitialiser url de l'image miniature dans le cas où la miniature n'est pas créé comme dans
//            le cas de bug ou pour les gifs
            $evenement->setPictureMin(null);

            //modification
            $evenement->upload();
            $this->entityManager->persist($evenement);

//            si l'internaute change d'image pour l'événement alors on supprime les anciennes
            if ($fichierPicture != $evenement->getPicture()) {
                if (file_exists(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPicture) && $fichierPicture != null) {
                    unlink(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPicture);
                }
                if (file_exists(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPictureMiniature) && $fichierPictureMiniature != null) {
                    unlink(__DIR__ . '/../../../../www/poubellesenor/upload/' . $fichierPictureMiniature);
                }
            }

            $this->entityManager->flush();

            return true;
        }
        return $form;
    }

    /**
     * @return array
     * gestion du formulaire de contact
     * retourne le tableau des éléments à passer à la vue
     */
    public function gereFormulaireConnexion(){

        // get the login error if there is one
        $error = $this->authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return array(
            'last_username' => $lastUsername,
            'error' => $error);
    }

    /**
     * @param $request
     * @return array
     * Créer le formulaire,  traite le formulaire si celui-ci est envoyé par l'internaute
     * retourne le tableau des éléments à passer à la vue
     */
    public function gereFormulaireContact($request){
        $contact = new Contact();

        $form = $this->formFactory->createBuilder(FormType::class, $contact)
            ->add('email', EmailType::class, array('attr' => array('autofocus' => 'autofocus')))
            ->add('objet', TextType::class)
            ->add('contenu', TextareaType::class)
            ->add('submit', SubmitType::class, array('attr' => array('value' => 'valider')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contenuEnPlus = " (Envoyé depuis le site poubelles-en-or.fr)";
            $message = \Swift_Message::newInstance()
                ->setSubject($contact->getObjet() . $contenuEnPlus)
                ->setFrom($contact->getEmail())
                ->setTo('compost_club@riseup.net')
                ->setReplyTo(array($contact->getEmail()))
                ->setBody($contact->getContenu());

            $this->mailer->send($message);

            // set flash messages
            $this->session->getFlashBag()->add('ValidationEnvoie', "L'émail a bien été envoyé !");

        }

        return array('form' => $form->createview());
    }




    /**
     * @return mixed
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }
}
