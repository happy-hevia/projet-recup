<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AvisLieu;
use AppBundle\Entity\Contact;
use AppBundle\Entity\DescriptionLieu;
use AppBundle\Form\DescriptionLieuType;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
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

class CarteController extends Controller
{


    /**
     * @route("/carte")
     * @return Response
     * Affiche la page de la carte
     */
    public function carteAction()
    {
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->add('note', ChoiceType::class,array(
                'choices'  => array(
                    "0" => 0,
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4" => 4,
                    "5" => 5,
                ),
            ))
            ->add('supermarche', CheckboxType::class, array(
                'label'    => 'Supermarché',
                'required' => false,
            ))
            ->add('epicerie', CheckboxType::class, array(
                'label'    => 'Epicerie',
                'required' => false,
            ))
            ->add('boulangerie', CheckboxType::class, array(
                'label'    => 'Boulangerie',
                'required' => false,
            ))
            ->add('marche', CheckboxType::class, array(
                'label'    => 'Marché',
                'required' => false,
            ))
            ->add('autre', CheckboxType::class, array(
                'label'    => 'Autre',
                'required' => false,
            ))
            ->add('facile', CheckboxType::class, array(
                'label'    => 'facile',
                'required' => false,
            ))
            ->add('difficile', CheckboxType::class, array(
                'label'    => 'difficile',
                'required' => false,
            ))
            ->add('jour', ChoiceType::class, array(
                'choices'  => array(
                    'lundi' => "lundi",
                    'mardi' => "mardi",
                    'mercredi' => "mercredi",
                    'jeudi' => "jeudi",
                    'vendredi' => "vendredi",
                    'samedi' => "samedi",
                    'dimanche' => "dimanche",
                    "" => "",
                ),
            ))
            ->getForm();

        return $this->render(':app:carte.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @route("/ajoutLieu")
     * @param Request $request
     * @return Response Affiche le formulaire d'ajout de lieu
     * Affiche le formulaire d'ajout de lieu
     */
    public function ajoutLieuAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $avisLieu = new AvisLieu();

        $form = $this->createFormBuilder($avisLieu)
            ->add('lieu', DescriptionLieuType::class)
            ->add('auteur', TextType::class)
            ->add('note', ChoiceType::class,array(
                'choices'  => array(
                    "0" => 0,
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4" => 4,
                    "5" => 5,
                    "" => ""
                ),
            ))
            ->add('commentaire', TextAreaType::class)
            ->add('jourSemaine', ChoiceType::class, array(
                'choices'  => array(
                    'lundi' => "lundi",
                    'mardi' => "mardi",
                    'mercredi' => "mercredi",
                    'jeudi' => "jeudi",
                    'vendredi' => "vendredi",
                    'samedi' => "samedi",
                    'dimanche' => "dimanche",
                    "" => "",
                ),
            ))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

//        variable qui définie si le formulaire est valide ou non
        $valide = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avisLieu);
            $em->flush();

            $valide = true;
        }
        return $this->render(':element:ajoutLieu.html.twig', array(
            'form' => $form->createview(),
            'valide' => $valide
        ));
    }

    /**
     * @route("/descriptionLieu/{id}")
     * @param DescriptionLieu $lieu
     * @param Request $request
     * @return Response Affiche le formulaire de modification du lieu
     * Affiche le formulaire de modification du lieu
     * @internal param DescriptionLieu $descriptionLieu
     */
    public function descriptionLieuAction(DescriptionLieu $lieu, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(DescriptionLieuType::class, $lieu)
            ->add('submit', SubmitType::class);

        $form->handleRequest($request);

        $valide = false;

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($lieu);
            $em->flush();
            $valide = true;
        }
        return $this->render(':element:descriptionLieu.html.twig', array(
            'form' => $form->createview(),
            'valide' => $valide
        ));
    }


    /**
     * @route("/avisLieu/{id}")
     * @param DescriptionLieu $descriptionLieu
     * @param Request $request
     * @return Response Affiche le formulaire d'ajour d'avis du lieu
     * Affiche le formulaire d'ajour d'avis du lieu
     * @internal param AvisLieu $avisLieu
     */
    public function avisLieuAction(DescriptionLieu $descriptionLieu, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $avisLieu = new AvisLieu();

        $form = $this->createFormBuilder($avisLieu)
            ->add('auteur', TextType::class)
            ->add('note', ChoiceType::class,array(
                'choices'  => array(
                    "0" => 0,
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4" => 4,
                    "5" => 5,
                    "" => ""
                ),
            ))
            ->add('commentaire', TextAreaType::class)
            ->add('jourSemaine', ChoiceType::class, array(
                'choices'  => array(
                    'lundi' => "lundi",
                    'mardi' => "mardi",
                    'mercredi' => "mercredi",
                    'jeudi' => "jeudi",
                    'vendredi' => "vendredi",
                    'samedi' => "samedi",
                    'dimanche' => "dimanche",
                    "" => "",
                ),
            ))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        //        variable qui définie si le formulaire est valide ou non
        $valide = false;

        if ($form->isSubmitted() && $form->isValid()) {

            $avisLieu->setLieu($descriptionLieu);
            $em->persist($avisLieu);
            $em->flush();
            $valide = true;
        }
        return $this->render(':element:avisLieu.html.twig', array(
            'form' => $form->createview(),
            'valide' => $valide
        ));

    }

    /**
     * @route("/descriptionPopup/{id}/{moyenne}")
     * @param DescriptionLieu $lieu
     * @param $moyenne
     * @param Request $request
     * @return Response Affiche le contenu de la popup de description du lieu
     * Affiche le contenu de la popup de description du lieu
     */
    public function descriptionPopupAction(DescriptionLieu $lieu, $moyenne, Request $request)
    {

        $tableauJourMoyenne = $request->query->all();

        $em = $this->getDoctrine()->getManager();

        $avis = $em->getRepository('AppBundle:AvisLieu')->findByLieu($lieu->getId());


        return $this->render(':element:descriptionPopup.html.twig', array(
            'lieu' => $lieu,
            "avis" => $avis,
            "moyenne" => $moyenne,
            "tableauJourMoyenne" => $tableauJourMoyenne
        ));
    }

    /**
     * @route("/afficheMarker")
     * @param Request $request
     * @return Response Génére le js qui va permettre d'afficher les markers sur la carte
     * Génére le js qui va permettre d'afficher les markers sur la carte
     */
    public function afficheMarkerAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

//        récupère l'ensemble des éléments du formulaire de recherche

        $note = $request->query->get('note') ? intval($request->query->get('note')) : 0;
        $supermarche = ($request->query->get('supermarche') == 'true') ? "supermarche" : "rien";
        $epicerie = ($request->query->get('epicerie') == 'true') ? "epicerie" : "rien";
        $boulangerie = ($request->query->get('boulangerie') == 'true') ? "boulangerie" : "rien";
        $marche = ($request->query->get('marche') == 'true') ? "marche" : "rien";
        $autre = ($request->query->get('autre') == 'true' ) ? "autre" : "rien";
        $facile = ($request->query->get('facile') == 'true') ? "facile" : "rien";
        $difficile = ($request->query->get('difficile') == 'true' ) ? "difficile" : "rien";
        $jour = $request->query->get('jour') ? $request->query->get('jour') : "";

//        récupère les avis qui remplisse les caractéristiques de la recherche personnalisée (sauf moyenne)

        $marker = $em->getRepository('AppBundle:AvisLieu')->getMarkersSelonRecherche($note,$supermarche,$epicerie,$boulangerie,$marche,$autre,$facile,$difficile,$jour);

//        Permet de vérifier que la note moyenne des commentaires est supérieur à la note voulu

        $tableauScoreTotal = array();
        $tableauNombre = array();

        $tableauMoyenne = array();

        foreach($marker as $m){
            if(!isset($tableauScoreTotal[$m->getLieu()->getId()] )){
                $tableauScoreTotal[$m->getLieu()->getId()] = 0;
            }
            if(!isset($tableauNombre[$m->getLieu()->getId()] )){
                $tableauNombre[$m->getLieu()->getId()] = 0;
            }
            $tableauScoreTotal[$m->getLieu()->getId()] = $tableauScoreTotal[$m->getLieu()->getId()] + $m->getNote();
            $tableauNombre[$m->getLieu()->getId()] = $tableauNombre[$m->getLieu()->getId()] + 1;
        }

        foreach($tableauScoreTotal as $s=>$el){
            if(($tableauScoreTotal[$s] / $tableauNombre[$s]) >= $note){
                $tableauMoyenne[$s] = $tableauScoreTotal[$s] / $tableauNombre[$s];
            }
        }

//        permet de récuperer les notes de chaque jour de la semaine

        $tableauJourScoreTotal = array();
        $tableauJourNombre = array();

        $tableauJourMoyenne = array();

        foreach($marker as $m){

            if(!isset($tableauJourScoreTotal[$m->getLieu()->getId()][$m->getJourSemaine()] )){
                $tableauJourScoreTotal[$m->getLieu()->getId()][$m->getJourSemaine()] = 0;
            }
            if(!isset($tableauJourNombre[$m->getLieu()->getId()][$m->getJourSemaine()] )){
                $tableauJourNombre[$m->getLieu()->getId()][$m->getJourSemaine()] = 0;
            }

            $tableauJourScoreTotal[$m->getLieu()->getId()][$m->getJourSemaine()] = $tableauJourScoreTotal[$m->getLieu()->getId()][$m->getJourSemaine()] + $m->getNote();
            $tableauJourNombre[$m->getLieu()->getId()][$m->getJourSemaine()] = $tableauJourNombre[$m->getLieu()->getId()][$m->getJourSemaine()] + 1;
        }

        foreach($tableauJourScoreTotal as $key=>$el){
            foreach($el as $key2 => $el2){

                $tableauJourMoyenne[$key][$key2] = $tableauJourScoreTotal[$key][$key2] / $tableauJourNombre[$key][$key2];
            }
        }




        return $this->render(':javascript:affichageMarker.js.twig', array(
            "marker" => $marker,
//            retourne un tableau des markeurs à afficher dont les clefs sont les identifiants du marker et la valeur est la moyenne
            "tableauMoyenne" => $tableauMoyenne,
//            retourne un tableau dont chaque markeur aura un tableau avec comme clef le jour de la semaine et comme valeur la moyenne de ce jour
            "tableauJourMoyenne" => $tableauJourMoyenne
        ));
    }

}
