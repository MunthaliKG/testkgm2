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
     * @var \DateTime
     *
     * @ORM\Column(name="date_procured", type="date", nullable=false)
     */
      
    private $date_procured;
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", nullable=true)
     */
    private $state;
    /**
     * @var \DateTime
     *
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
     * @ORM\Column(name="available_in_rc", type="string", nullable=false)
     */
    private $available_in_rc;

 /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;
    /**
     * @var text
     *
     * @ORM\Column(name="updates", type="text")
     */
    private $updates;
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
     * Get date_procured
     *
     * @return \DateTime 
     */
    public function getDateProcured()
    {
        return $this->date_procured;
    }
    /**
     * Set date_procured
     *
     * @param \DateTime $date_procured
     * @return ResourceRoom
     */
    public function setDateProcured($date_procured)
    {
        $this->date_procured = $date_procured;

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
     * Set available_in_rc
     *
     * @param string $available_in_rc
     * @return ResourceRoom
     */
    public function setAvailableInRc($available_in_rc)
    {
        $this->available_in_rc = $available_in_rc;

        return $this;
    }

    /**
     * Get available_in_rc
     *
     * @return string 
     */
    public function getAvailableInRc()
    {
        return $this->available_in_rc;
    }
    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ResourceRoom
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
     /**
     * Set state
     *
     * @param string $state
     * @return Snt
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }
     /**
     * Set updates
     *
     * @param text $updates
     * @return ResourceRoom
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;

        return $this;
    }
    /**
     * Get updates
     *
     * @return text
     */
    public function getUpdates()
    {
        return $this->updates;
    }
}
