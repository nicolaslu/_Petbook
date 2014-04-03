<?php
// src/Social/UserBundle/Entity/User.php

namespace Social\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="path" ,type="string", length=255, nullable=true)
     */
    private $path;
	
	protected $friendsWithMe;

    protected $myFriends;

    public function __construct()
    {
        parent::__construct();
        // your own logic
		$this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $pPath
     * @return Publication
     */
    public function setPath($pPath)
    {
        $this->path = $pPath;

        return $this;
    }
}