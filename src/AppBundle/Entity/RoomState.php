<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoomState
 *
 * @ORM\Table(name="room_state", indexes={@ORM\Index(name="fk_facility_state_school1_idx", columns={"emiscode"})})
 * @ORM\Entity
 */
class RoomState
{
    /**
     * @var string
     *
     * @ORM\Column(name="enough_light", type="string", nullable=false)
     */
    private $enoughLight;

    /**
     * @var string
     *
     * @ORM\Column(name="enough_space", type="string", nullable=false)
     */
    private $enoughSpace;

    /**
     * @var integer
     *
     * @ORM\Column(name="adaptive_chairs", type="integer", nullable=false)
     */
    private $adaptiveChairs;

    /**
     * @var string
     *
     * @ORM\Column(name="access", type="string", nullable=false)
     */
    private $access;

    /**
     * @var string
     *
     * @ORM\Column(name="enough_ventilation", type="string", nullable=false)
     */
    private $enoughVentilation;

    /**
     * @var string
     *
     * @ORM\Column(name="other_observations", type="string", length=100, nullable=true)
     */
    private $otherObservations;

    /**
     * @var string
     *
     * @ORM\Column(name="room_id", type="string", length=2)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRoom;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="year_started", type="year", nullable=false)
     */
    private $year_started;

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
     * Set enoughLight
     *
     * @param string $enoughLight
     * @return RoomState
     */
    public function setEnoughLight($enoughLight)
    {
        $this->enoughLight = $enoughLight;

        return $this;
    }

    /**
     * Get enoughLight
     *
     * @return string 
     */
    public function getEnoughLight()
    {
        return $this->enoughLight;
    }

    /**
     * Set enoughSpace
     *
     * @param string $enoughSpace
     * @return RoomState
     */
    public function setEnoughSpace($enoughSpace)
    {
        $this->enoughSpace = $enoughSpace;

        return $this;
    }

    /**
     * Get enoughSpace
     *
     * @return string 
     */
    public function getEnoughSpace()
    {
        return $this->enoughSpace;
    }

    /**
     * Set adaptiveChairs
     *
     * @param integer $adaptiveChairs
     * @return RoomState
     */
    public function setAdaptiveChairs($adaptiveChairs)
    {
        $this->adaptiveChairs = $adaptiveChairs;

        return $this;
    }

    /**
     * Get adaptiveChairs
     *
     * @return integer 
     */
    public function getAdaptiveChairs()
    {
        return $this->adaptiveChairs;
    }

    /**
     * Set access
     *
     * @param string $access
     * @return RoomState
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return string 
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set enoughVentilation
     *
     * @param string $enoughVentilation
     * @return RoomState
     */
    public function setEnoughVentilation($enoughVentilation)
    {
        $this->enoughVentilation = $enoughVentilation;

        return $this;
    }

    /**
     * Get enoughVentilation
     *
     * @return string 
     */
    public function getEnoughVentilation()
    {
        return $this->enoughVentilation;
    }

    /**
     * Set otherObservations
     *
     * @param string $otherObservations
     * @return RoomState
     */
    public function setOtherObservations($otherObservations)
    {
        $this->otherObservations = $otherObservations;

        return $this;
    }

    /**
     * Get otherObservations
     *
     * @return string 
     */
    public function getOtherObservations()
    {
        return $this->otherObservations;
    }

    /**
     * Set idRoom
     *
     * @param string $idRoom
     * @return RoomState
     */
    public function setIdRoom($idRoom)
    {
        $this->idRoom = $idRoom;

        return $this;
    }

    /**
     * Get idRoom
     *
     * @return string 
     */
    public function getIdRoom()
    {
        return $this->idRoom;
    }
    
    /**
     * Set year_started
     *
     * @param \DateTime $year_started
     * @return RoomState
     */
    public function setYearStarted($year_started)
    {
        $this->year_started = $year_started;

        return $this;
    }

    /**
     * Get year_started
     *
     * @return \DateTime 
     */
    public function getYear()
    {
        return $this->year_started;
    }

    /**
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return RoomState
     */
    public function setEmiscode($emiscode)
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
}
