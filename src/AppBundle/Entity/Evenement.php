<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvenementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Evenement
{
    /**
     * @Assert\Image(maxSize="1800000")
     */
    private $file;

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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=7, max=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="time", nullable=true)
     **/

    private $hour;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="date", nullable=true)
     * @Assert\Range(min="-18 years", max="+18 years")
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="text", nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_min", type="text", nullable=true)
     */
    private $picture_min;


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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Evenement
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Evenement
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Evenement
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get hour
     *
     * @return \DateTime
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set hour
     *
     * @param \DateTime $hour
     *
     * @return Evenement
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Evenement
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Evenement
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return Evenement
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedWithCurrentDate()
    {
        $this->created = new \DateTime();
    }

    public function getAbsolutePath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadRootDir() . '/' . $this->picture;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../www/poubellesenor/' . $this->getUploadDir();
    }


//     uppload d'image

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'upload';
    }

    public function getWebPath()
    {
        return null === $this->picture
            ? null
            : $this->getUploadDir() . '/' . $this->picture;
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        $pictureNewName = uniqid().$this->getFile()->getClientOriginalName();
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $pictureNewName
        );

        // set the path property to the filename where you've saved the file
        $this->picture = $pictureNewName;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @ORM\PreUpdate
     */
    public function generateMiniaturePreUpdate()
    {


        $extensionPicture = pathinfo($this->picture, PATHINFO_EXTENSION);
        $extensionsMiniaturesAllows = array('jpg', 'jpeg', 'png');

        if (in_array($extensionPicture, $extensionsMiniaturesAllows)) {


            // créer la miniature à partir de l'image originel
            $max = 178;
            if ($extensionPicture == "jpg" || $extensionPicture == "jpeg") {
                $img = imagecreatefromjpeg('upload/' . $this->picture);

            } else if ($extensionPicture == "png") {
                $img = imagecreatefrompng('upload/' . $this->picture);
            }
            $x = imagesx($img);
            $y = imagesy($img);

            $ratio = $x / $y;

            $xoffset = 0;
            $yoffset = 0;

            if ($x > $y) {
                $ny = $max;
                $nx = $ny * $ratio;
                $xoffset = ($x - $y) / 2;
            } else {
                $nx = $max;
                $ny = $nx * $ratio;
                $yoffset = ($y - $x) / 2;
            }


            $nimg = imagecreatetruecolor($max, $max);
            imagecopyresampled($nimg, $img, 0, 0, $xoffset, $yoffset, $nx, $ny, $x, $y);

//        déplace la miniature dans le bon dossier sous le bon nom selon l'extension
//        et ajoute le nom du fichier à l'entité avant de persist

            if ($extensionPicture == "jpeg" || $extensionPicture == "jpg") {
                $nomFichier = 'min-' . $this->picture;
                fopen($nomFichier, 'a+');
                imagepng($nimg, $this->getUploadRootDir() . '/' . $nomFichier);
                $this->picture_min = $nomFichier;
            } else if ($extensionPicture == "png") {
                $nomFichier = 'min-' . $this->picture;
                fopen($nomFichier, 'a+');
                imagejpeg($nimg, $this->getUploadRootDir() . '/' . $nomFichier);
                $this->picture_min = $nomFichier;
            }
        }

    }

    /**
     * @ORM\PrePersist
     */
    public function generateMiniaturePrePersist()
    {


        $extensionPicture = pathinfo($this->picture, PATHINFO_EXTENSION);
        $extensionsMiniaturesAllows = array('jpg', 'jpeg', 'png');
//        exit(dump(in_array($extensionPicture, $extensionsMiniaturesAllows)));

        if (in_array($extensionPicture, $extensionsMiniaturesAllows)) {


            // créer la miniature à partir de l'image originel
            $max = 178;
            if ($extensionPicture == "jpg" || $extensionPicture == "jpeg") {
                $img = imagecreatefromjpeg('upload/' . $this->picture);

            } else if ($extensionPicture == "png") {
                $img = imagecreatefrompng('upload/' . $this->picture);
            }
            $x = imagesx($img);
            $y = imagesy($img);

            $ratio = $x / $y;

            $xoffset = 0;
            $yoffset = 0;

            if ($x > $y) {
                $ny = $max;
                $nx = $ny * $ratio;
                $xoffset = ($x - $y) / 2;
            } else {
                $nx = $max;
                $ny = $nx * $ratio;
                $yoffset = ($y - $x) / 2;
            }


            $nimg = imagecreatetruecolor($max, $max);
            imagecopyresampled($nimg, $img, 0, 0, $xoffset, $yoffset, $nx, $ny, $x, $y);

//        déplace la miniature dans le bon dossier sous le bon nom selon l'extension
//        et ajoute le nom du fichier à l'entité avant de persist

            if ($extensionPicture == "jpeg" || $extensionPicture == "jpg") {
                $nomFichier = 'min-' . $this->picture;
                fopen($nomFichier, 'a+');
                imagepng($nimg, $this->getUploadRootDir() . '/' . $nomFichier);
                $this->picture_min = $nomFichier;
            } else if ($extensionPicture == "png") {
                $nomFichier = 'min-' . $this->picture;
                fopen($nomFichier, 'a+');
                imagejpeg($nimg, $this->getUploadRootDir() . '/' . $nomFichier);
                $this->picture_min = $nomFichier;
            }
        }

    }


    /**
     * @ORM\PreRemove
     */
    public function removePictureFile()
    {
        if (file_exists($this->getUploadRootDir() . '/' . $this->picture) && $this->picture_min != null) {
            unlink($this->getUploadRootDir() . '/' . $this->picture);
        }
        if (file_exists($this->getUploadRootDir() . '/' . $this->picture_min) && $this->picture_min != null) {
            unlink($this->getUploadRootDir() . '/' . $this->picture_min);
        }
    }



    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Set pictureMin
     *
     * @param string $pictureMin
     *
     * @return Evenement
     */
    public function setPictureMin($pictureMin)
    {
        $this->picture_min = $pictureMin;

        return $this;
    }

    /**
     * Get pictureMin
     *
     * @return string
     */
    public function getPictureMin()
    {
        return $this->picture_min;
    }
}
