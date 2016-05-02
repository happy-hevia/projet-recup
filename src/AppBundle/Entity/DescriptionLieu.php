<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * DescriptionLieu
 *
 * @ORM\Table(name="description_lieu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DescriptionLieuRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DescriptionLieu
{
//    tout ce qui concerne les images est mis de côtés pour l'instant


//    /**
//     * @Assert\Image(maxSize="1800")
//     */
//    private $file;

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
     * @ORM\Column(name="localisation", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 40)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="acces", type="text")
     * @Assert\NotBlank()
     */
    private $acces;


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
     * Set localisation
     *
     * @param string $localisation
     *
     * @return DescriptionLieu
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return DescriptionLieu
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return DescriptionLieu
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return DescriptionLieu
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return DescriptionLieu
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @return string
     */
    public function getAcces()
    {
        return $this->acces;
    }

    /**
     * @param string $acces
     */
    public function setAcces($acces)
    {
        $this->acces = $acces;
    }

    /**
     * @ORM\PrePersist
     * permet de générer la date d'aujourd'hui avant de mettre dans la base de donnée
     */
    public function setDateCreationWithCurrentDate()
    {
        $this->dateCreation = new \DateTime();
    }

//    /**
//     * Get file.
//     *
//     * @return UploadedFile
//     */
//    public function getFile()
//    {
//        return $this->file;
//    }
//
//    /**
//     * Sets file.
//     *
//     * @param UploadedFile $file
//     */
//    public function setFile(UploadedFile $file = null)
//    {
//        $this->file = $file;
//    }
//
//
////    Helper fonction pour l'upload d'image
//
//    public function getAbsolutePath()
//    {
//        return null === $this->image
//            ? null
//            : $this->getUploadRootDir() . '/' . $this->image;
//    }
//
////    TODO modifier le chemin
//    protected function getUploadRootDir()
//    {
//        // the absolute directory path where uploaded
//        // documents should be saved
//        return __DIR__ . '/../../../web/' . $this->getUploadDir();
//    }
//
//
////     uppload d'image
//
//    protected function getUploadDir()
//    {
//        // get rid of the __DIR__ so it doesn't screw up
//        // when displaying uploaded doc/image in the view.
//        return 'upload';
//    }
//
//    public function getWebPath()
//    {
//        return null === $this->image
//            ? null
//            : $this->getUploadDir() . '/' . $this->image;
//    }
//
//    public function upload()
//    {
//        // the file property can be empty if the field is not required
//        if (null === $this->getFile()) {
//            return ;
//        }
//        $pictureNewName = uniqid().$this->getFile()->getClientOriginalName();
//        // use the original file name here but you should
//        // sanitize it at least to avoid any security issues
//
//        // move takes the target directory and then the
//        // target filename to move to
//        $this->getFile()->move(
//            $this->getUploadRootDir(),
//            $pictureNewName
//        );
//
//        // set the path property to the filename where you've saved the file
//        $this->image = $pictureNewName;
//
//        // clean up the file property as you won't need it anymore
//        $this->file = null;
//    }



}
