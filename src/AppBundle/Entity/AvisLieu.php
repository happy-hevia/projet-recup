<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * AvisLieu
 *
 * @ORM\Table(name="avis_lieu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvisLieuRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AvisLieu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=true)
     * @Assert\Length(min = 2, max = 20)
     */
    private $auteur;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     * @Assert\NotBlank()
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     * @Assert\Length(max = 255)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="jour_semaine", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $jourSemaine;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DescriptionLieu", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $lieu;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return AvisLieu
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return AvisLieu
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     *
     * @return AvisLieu
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return AvisLieu
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return AvisLieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set jourSemaine
     *
     * @param string $jourSemaine
     *
     * @return AvisLieu
     */
    public function setJourSemaine($jourSemaine)
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    /**
     * Get jourSemaine
     *
     * @return string
     */
    public function getJourSemaine()
    {
        return $this->jourSemaine;
    }


    /**
     * @ORM\PrePersist
     */
    public function setDateAjoutWithCurrentDate()
    {
        $this->dateAjout = new \DateTime();
    }


}
