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
     * @ORM\Column(name="accessible", type="string", nullable=false)
     */
    private $accessible;

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
    private $roomId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $year;

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
     * Set accessible
     *
     * @param string $accessible
     * @return RoomState
     */
    public function setAccessible($accessible)
    {
        $this->accessible = $accessible;

        return $this;
    }

    /**
     * Get accessible
     *
     * @return string 
     */
    public function getAccessible()
    {
        return $this->accessible;
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
     * Set roomId
     *
     * @param string $roomId
     * @return RoomState
     */
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;

        return $this;
    }

    /**
     * Get roomId
     *
     * @return string 
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     * @return RoomState
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return RoomState
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
}
