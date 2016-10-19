<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 19/10/2016
 * Time: 14:45
 */

namespace AppBundle\Services;


use AppBundle\Entity\AvisLieu;
use AppBundle\Form\DescriptionLieuType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;

class GestionFormulaireCarte
{
    private $entityManager;
    private $formFactory;

    public function __construct(EntityManager $entityManager, FormFactory $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function gereFomulaireRecherche(){
        $defaultData = array();
        $form = $this->formFactory->createBuilder(FormType::class, $defaultData)
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

        return array("form" => $form->createView());
    }

    public function gereFormulaireAjoutLieu($request){

        $avisLieu = new AvisLieu();

        $form = $this->formFactory->createBuilder(FormType::class, $avisLieu)
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
            $this->entityManager->persist($avisLieu);
            $this->entityManager->flush();

            $valide = true;
        }

        return array(
            'form' => $form->createview(),
            'valide' => $valide
        );
    }

    public function gereFormulaireModificationLieu($lieu, $request){
        $form = $this->formFactory->create(DescriptionLieuType::class, $lieu)
            ->add('submit', SubmitType::class);

        $form->handleRequest($request);

        $valide = false;

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($lieu);
            $this->entityManager->flush();
            $valide = true;
        }

        return array(
            'form' => $form->createview(),
            'valide' => $valide
        );
    }

    public function gereFormulaireAvisLieu($descriptionLieu, $request){
        $avisLieu = new AvisLieu();

        $form = $this->formFactory->createBuilder(FormType::class, $avisLieu)
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
            $this->entityManager->persist($avisLieu);
            $this->entityManager->flush();
            $valide = true;
        }

        return array(
            'form' => $form->createview(),
            'valide' => $valide
        );
    }


}
