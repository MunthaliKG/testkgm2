<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResourceRoom
 *
 * @ORM\Table(name="school_has_need", indexes={@ORM\Index(name="fk_school_has_need_need1_idx", columns={"idneed"}), @ORM\Index(name="fk_school_has_need_school1_idx", columns={"emiscode"})})
 * @ORM\Entity
 */
class ResourceRoom
{    
    
    /**
     * @var string
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")     
     * @ORM\Column(name="year_recorded", type="year", nullable=false)
     */
    
    private $year_recorded;

    /**
     * @var \AppBundle\Entity\Need
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Need")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idneed", referencedColumnName="idneed")
     * })
     */
    private $idneed;

    /**
     * @var \AppBundle\Entity\School
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\School")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emiscode", referencedColumnName="emiscode")
     * })
     */
    private $emiscode;

     /**
     * @var string
     *
     * @ORM\Column(name="provided_by", type="string", nullable=false)
     */
    private $provided_by;

     /**
     * @var integer
     *
     * @ORM\Column(name="quantity_available", type="integer", nullable=false)
     * 
     */
    private $quantity_available;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantity_in_use", type="integer", nullable=false)
     * 
     */
    private $quantity_in_use;
    /**
     * @var integer
     *
     * @ORM\Column(name="quantity_required", type="integer", nullable=false)
     * 
     */
    private $quantity_required;
    /**
     * @var string
     *
     * @ORM\Column(name="available", type="string", nullable=false)
     * 
     */
    private $available;
   
    /**
     * Set year_recorded
     *
     * @param \DateTime $year_recorded
     * @return ResourceRoom
     */
    public function setYearRecorded($year_recorded)
    {
        $this->year_recorded = $year_recorded;

        return $this;
    }

    

    /**
     * Get year_recorded
     *
     * @return \DateTime 
     */
    public function getYearRecorded()
    {
        return $this->year_recorded;
    }

    /**
     * Set idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     * @return ResourceRoom
     */
    public function setIdneed(\AppBundle\Entity\Need $idneed)
    {
        $this->idneed = $idneed;

        return $this;
    }

    /**
     * Get idneed
     *
     * @return \AppBundle\Entity\Need 
     */
    public function getIdneed()
    {
        return $this->idneed;
    }

    /**
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return ResourceRoom
     */
    public function setEmiscode(\AppBundle\Entity\School $emiscode)
    {
        $this->emiscode = $emiscode;

        return $this;
    }

    /**
     * Get emiscode
     *
     * @return \AppBundle\Entity\School 
     */
    public function getEmiscode()
    {
        return $this->emiscode;
    }
    
     /**
     * Set provided_by
     *
     * @param string $provided_by
     * @return ResourceRoom
     */
    public function setProvidedBy($provided_by)
    {
        $this->provided_by = $provided_by;

        return $this;
    }

    /**
     * Get provided_by
     *
     * @return string 
     */
    public function getProvidedBy()
    {
        return $this->provided_by;
    }
    
    /**
     * Set quantity_in_use
     *
     * @param integer $quantity_in_use
     * @return ResourceRoom
     */
    public function setQuantityInUse($quantity_in_use)
    {
        $this->quantity_in_use = $quantity_in_use;

        return $this;
    }

    /**
     * Get quantity_in_use
     *
     * @return integer 
     */
    public function getQuantityInUse()
    {
        return $this->quantity_in_use;
    }
    //
     /**
     * Set quantity_available
     *
     * @param integer $quantity_available
     * @return ResourceRoom
     */
    public function setQuantityAvailable($quantity_available)
    {
        $this->quantity_available = $quantity_available;

        return $this;
    }

    /**
     * Get quantity_available
     *
     * @return integer 
     */
    public function getQuantityAvailable()
    {
        return $this->quantity_available;
    }
    
    //
     /**
     * Set quantity_required
     *
     * @param integer $quantity_required
     * @return ResourceRoom
     */
    public function setQuantityRequired($quantity_required)
    {
        $this->quantity_required = $quantity_required;

        return $this;
    }

    /**
     * Get quantity_required
     *
     * @return integer 
     */
    public function getQuantityRequired()
    {
        return $this->quantity_required;
    }
    
        /**
     * Set available
     *
     * @param integer $available
     * @return ResourceRoom
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer 
     */
    public function getAvailable()
    {
        return $this->available;
    }
}
