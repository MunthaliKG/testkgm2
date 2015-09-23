<?php

namespace AppBundle\Entity;

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
     * @var string
     *
     * @ORM\Column(name="ufirst_name", type="string", nullable=false)
     */
    private $firstName;
    /**
     * @var string
     *
     * @ORM\Column(name="ulast_name", type="string", nullable=false)
     */
    private $lastName;
    /**
     * @var integer
     *
     * @ORM\Column(name="access_level", type="integer", nullable=false)
     */
    private $accessLevel;
    /**
     * @var integer
     *
     * @ORM\Column(name="access_domain", type="integer", nullable=false)
     */
    private $accessDomain;
    /**
     * @var integer
     *
     * @ORM\Column(name="allowed_actions", type="integer", nullable=false)
     */
    private $allowedActions;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    function __construct(){
        parent::__construct();
        $this->isActive = true;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    /**
     * Set accessLevel
     *
     * @param integer $accessLevel
     * @return User
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;
        return $this;
    }
    /**
     * Set accessDomain
     *
     * @param integer $accessDomain
     * @return User
     */
    public function setAccessDomain($accessDomain)
    {
        $this->accessDomain = $accessDomain;
        return $this;
    }
    /**
     * Set allowedActions
     *
     * @param integer $allowedActions
     * @return User
     */
    public function setAllowedActions($allowedActions)
    {
        $this->allowedActions = $allowedActions;
        return $this;
    }
    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /**
     * Get accessLevel
     *
     * @return integer 
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }
    /**
     * Get accessDomain
     *
     * @return integer 
     */
    public function getAccessDomain()
    {
        return $this->accessDomain;
    }
    /**
     * Get allowedActions
     *
     * @return integer 
     */
    public function getAllowedActions()
    {
        return $this->allowedActions;
    }
    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

}
