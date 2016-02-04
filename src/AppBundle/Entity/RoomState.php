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
     * @var year
     *
     * @ORM\Column(name="year", type="year", nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="enough_light", type="string", nullable=false)
     */
    private $enoughLight;
    
    /**
     * @var string
     *
     * @ORM\Column(name="noise_free", type="string", nullable=false)
     */
    private $noiseFree;

    /**
     * @var string
     *
     * @ORM\Column(name="enough_space", type="string", nullable=false)
     */
    private $enoughSpace;

    /**
     * @var string
     *
     * @ORM\Column(name="adaptive_chairs", type="string", nullable=false)
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
     * @ORM\Column(name="room_type", type="string", nullable=false)
     */
    private $roomType;

    /**
     * @var string
     *
     * @ORM\Column(name="room_id", type="string", length=3)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $roomId;

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
     * Set year
     *
     * @param year $year
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
     * @return year 
     */
    public function getYear()
    {
        return $this->year;
    }

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
     * @param string $adaptiveChairs
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
     * @return string 
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
     * Set roomType
     *
     * @param string $roomType
     * @return RoomState
     */
    public function setRoomType($roomType)
    {
        $this->roomType = $roomType;

        return $this;
    }

    /**
     * Get roomType
     *
     * @return string 
     */
    public function getRoomType()
    {
        return $this->roomType;
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
    
    /**
     * Set noiseFree
     *
     * @param string $noiseFree
     * @return RoomState
     */
    public function setNoiseFree($noiseFree)
    {
        $this->noiseFree = $noiseFree;

        return $this;
    }

    /**
     * Get noiseFree
     *
     * @return string 
     */
    public function getNoiseFree()
    {
        return $this->noiseFree;
    }
}
