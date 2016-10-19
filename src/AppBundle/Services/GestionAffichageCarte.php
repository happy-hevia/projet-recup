<?php
/**
 * Created by PhpStorm.
 * User: Jérémie
 * Date: 19/10/2016
 * Time: 14:45
 */

namespace AppBundle\Services;


use AppBundle\Entity\AvisLieu;
use Doctrine\ORM\EntityManager;

class GestionAffichageCarte
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function gereAffichageMarqueur($request){

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

        $marker = $this->entityManager->getRepository('AppBundle:AvisLieu')->getMarkersSelonRecherche($note,$supermarche,$epicerie,$boulangerie,$marche,$autre,$facile,$difficile,$jour);

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


        return array(
            "marker" => $marker,
//            retourne un tableau des markeurs à afficher dont les clefs sont les identifiants du marker et la valeur est la moyenne
            "tableauMoyenne" => $tableauMoyenne,
//            retourne un tableau dont chaque markeur aura un tableau avec comme clef le jour de la semaine et comme valeur la moyenne de ce jour
            "tableauJourMoyenne" => $tableauJourMoyenne
        );
    }

}
