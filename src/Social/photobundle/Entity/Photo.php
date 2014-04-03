<?php

namespace Social\PhotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 */
class Photo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $auteur;

   /**
   * @ORM\ManyToOne(targetEntity="Social\PhotoBundle\Entity\Album")
   * @ORM\JoinColumn(nullable=false)
   */
	private $idAlbum;
	
	/**
     * @var datetime
     */
    private $date;
	
	private $image;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Photo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Photo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set auteur
     *
     * @param integer $auteur
     * @return Photo
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return integer 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

	/**
     * Set date
     *
     * @param \DateTime $date
     * @return Album
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
     }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }
	
	  /**
	   * Set idAlbum
	   *
	   * @param Social\PhotoBundle\Entity\Album $idAlbum
	   */
	  public function setidAlbum(\Social\PhotoBundle\Entity\Album $idAlbum)
	  {
		$this->idAlbum = $idAlbum;
	  }

	  /**
	   * Get idAlbum
	   *
	   * @return Social\PhotoBundle\Entity\Album
	   */
	  public function getidAlbum()
	  {
		return $this->idAlbum;
	  }
	
	public function upload()
	{
		// Si jamais il n'y a pas de fichier (champ facultatif)
		if (null === $this->image) {
		  return;
		}

		// On garde le nom original du fichier de l'internaute
		$name = $this->image->getClientOriginalName();

		// On déplace le fichier envoyé dans le répertoire de notre choix
		$this->image->move($this->getUploadRootDir(), $name);

		// On sauvegarde le nom de fichier dans notre attribut $url
		$this->url = $name;

	}

	public function getUploadDir()
	{
		// On retourne le chemin relatif vers l'image pour un navigateur
		return 'img/uploads/img';
	}

	protected function getUploadRootDir()
	{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}
	public function __toString()
	{
		return strval($this->id);
	}
}
