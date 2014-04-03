<?php

namespace Social\WallBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 */
class Publication
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity="Social\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @var integer
     */
    private $idUser;

    /**
     * @var string
     */
    private $idWriter;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \Datetime();
    }

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
     * Set texte
     *
     * @param string $texte
     * @return Publication
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     * @return Publication
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set date
     * @param Datetime $date
     * @return Publication
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

    /**
     * Set writer
     *
     * @param string $username
     * @return Publication
     */
    public function setIdWriter($username)
    {
        $this->idWriter = $username;

        return $this;
    }

    /**
     * Get writer
     *
     * @return string 
     */
    public function getIdWriter()
    {
        return $this->idWriter;
    }

    public function setUpdated()
    {
        // WILL be saved in the database
        $this->date = new \DateTime("now");
    }

}
