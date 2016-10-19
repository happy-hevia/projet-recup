<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AvisLieu;
use AppBundle\Entity\DescriptionLieu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;

class CarteController extends Controller
{


    /**
     * @route("/carte")
     * @return Response
     * Affiche la page de la carte
     */
    public function carteAction()
    {
        return $this->render(':app:carte.html.twig', $this->get("app_bundle.app.gestion_formulaire_carte")->gereFomulaireRecherche());
    }

    /**
     * @route("/ajoutLieu")
     * @param Request $request
     * @return Response Affiche le formulaire d'ajout de lieu
     * Affiche le formulaire d'ajout de lieu
     */
    public function ajoutLieuAction(Request $request)
    {
        return $this->render(':element:ajoutLieu.html.twig', $this->get("app_bundle.app.gestion_formulaire_carte")->gereFormulaireAjoutLieu($request));
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

        return $this->render(':element:descriptionLieu.html.twig', $this->get('app_bundle.app.gestion_formulaire_carte')->gereFormulaireModificationLieu($lieu, $request));
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

        return $this->render(':element:avisLieu.html.twig', $this->get('app_bundle.app.gestion_formulaire_carte')->gereFormulaireAvisLieu($descriptionLieu, $request));

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


        return $this->render(':javascript:affichageMarker.js.twig', $this->get('app_bundle.app.gestion_affichage_carte')->gereAffichageMarqueur($request));
    }

}
