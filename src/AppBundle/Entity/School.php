<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * School
 *
 * @ORM\Table(name="school", indexes={@ORM\Index(name="fk_school_zone1_idx", columns={"idzone"}), @ORM\Index(name="fk_school_district1_idx", columns={"iddistrict"})})
 * @ORM\Entity
 */
class School
{
    /**
     * @var string
     *
     * @ORM\Column(name="school_name", type="string", length=50, nullable=false)
     */
    private $schoolName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=250, nullable=false)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="emiscode", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emiscode;

    /**
     * @var \AppBundle\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddistrict", referencedColumnName="iddistrict")
     * })
     */
    private $iddistrict;

    /**
     * @var \AppBundle\Entity\Zone
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Zone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idzone", referencedColumnName="idzone")
     * })
     */
    private $idzone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idneed = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set schoolName
     *
     * @param string $schoolName
     * @return School
     */
    public function setSchoolName($schoolName)
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    /**
     * Get schoolName
     *
     * @return string 
     */
    public function getSchoolName()
    {
        return $this->schoolName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return School
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get emiscode
     *
     * @return integer 
     */
    public function getEmiscode()
    {
        return $this->emiscode;
    }

    /**
     * Set iddistrict
     *
     * @param \AppBundle\Entity\District $iddistrict
     * @return School
     */
    public function setIddistrict(\AppBundle\Entity\District $iddistrict = null)
    {
        $this->iddistrict = $iddistrict;

        return $this;
    }

    /**
     * Get iddistrict
     *
     * @return \AppBundle\Entity\District 
     */
    public function getIddistrict()
    {
        return $this->iddistrict;
    }

    /**
     * Set idzone
     *
     * @param \AppBundle\Entity\Zone $idzone
     * @return School
     */
    public function setIdzone(\AppBundle\Entity\Zone $idzone = null)
    {
        $this->idzone = $idzone;

        return $this;
    }

    /**
     * Get idzone
     *
     * @return \AppBundle\Entity\Zone 
     */
    public function getIdzone()
    {
        return $this->idzone;
    }

    /**
     * Add idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     * @return School
     */
    public function addIdneed(\AppBundle\Entity\Need $idneed)
    {
        $this->idneed[] = $idneed;

        return $this;
    }

    /**
     * Remove idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     */
    public function removeIdneed(\AppBundle\Entity\Need $idneed)
    {
        $this->idneed->removeElement($idneed);
    }

    /**
     * Get idneed
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdneed()
    {
        return $this->idneed;
    }
}
