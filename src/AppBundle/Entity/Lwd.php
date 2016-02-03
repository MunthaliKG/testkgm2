<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lwd
 *
 * @ORM\Table(name="lwd", indexes={@ORM\Index(name="fk_lwd_guardian1_idx", columns={"idguardian"})})
 * @ORM\Entity
 */
class Lwd
{
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=25, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="home_address", type="string", length=100, nullable=false)
     */
    private $homeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", nullable=false)
     */
    private $sex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date", nullable=false)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="guardian_relationship", type="string", nullable=false)
     */
    private $guardianRelationship;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status_of_parent", type="string", nullable=false)
     */
    private $statusOfParent;

    /**
     * @var string
     *
     * @ORM\Column(name="non_relative", type="string", length=25, nullable=true)
     */
    private $nonRelative;

    /**
     * @var integer
     *
     * @ORM\Column(name="idlwd", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlwd;

    /**
     * @var \AppBundle\Entity\Guardian
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Guardian")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idguardian", referencedColumnName="idguardian")
     * })
     */
    private $idguardian;

/**
     * Set statusOfParent
     *
     * @param string $statusOfParent
     * @return Lwd
     */
    public function setStatusOfParent($statusOfParent)
    {
        $this->statusOfParent = $statusOfParent;

        return $this;
    }

    /**
     * Get statusOfParent
     *
     * @return string 
     */
    public function getStatusOfParent()
    {
        return $this->statusOfParent;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Lwd
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

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
     * Set lastName
     *
     * @param string $lastName
     * @return Lwd
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
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
     * Set homeAddress
     *
     * @param string $homeAddress
     * @return Lwd
     */
    public function setHomeAddress($homeAddress)
    {
        $this->homeAddress = $homeAddress;

        return $this;
    }

    /**
     * Get homeAddress
     *
     * @return string 
     */
    public function getHomeAddress()
    {
        return $this->homeAddress;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return Lwd
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return Lwd
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime 
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set guardianRelationship
     *
     * @param string $guardianRelationship
     * @return Lwd
     */
    public function setGuardianRelationship($guardianRelationship)
    {
        $this->guardianRelationship = $guardianRelationship;

        return $this;
    }

    /**
     * Get guardianRelationship
     *
     * @return string 
     */
    public function getGuardianRelationship()
    {
        return $this->guardianRelationship;
    }

    /**
     * Set nonRelative
     *
     * @param string $nonRelative
     * @return Lwd
     */
    public function setNonRelative($nonRelative)
    {
        $this->nonRelative = $nonRelative;

        return $this;
    }

    /**
     * Get nonRelative
     *
     * @return string 
     */
    public function getNonRelative()
    {
        return $this->nonRelative;
    }

    /**
     * Get idlwd
     *
     * @return integer 
     */
    public function getIdlwd()
    {
        return $this->idlwd;
    }

    /**
     * Set idlwd
     *
     * @param integer $idlwd
     * @return Lwd
     */
    public function setIdlwd($idlwd)
    {
        $this->idlwd = $idlwd;
    }
    
    /**
     * Set idguardian
     *
     * @param \AppBundle\Entity\Guardian $idguardian
     * @return Lwd
     */
    public function setIdguardian(\AppBundle\Entity\Guardian $idguardian = null)
    {
        $this->idguardian = $idguardian;

        return $this;
    }

    /**
     * Get idguardian
     *
     * @return \AppBundle\Entity\Guardian 
     */
    public function getIdguardian()
    {
        return $this->idguardian;
    }
}
