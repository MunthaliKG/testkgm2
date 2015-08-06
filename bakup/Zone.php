<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Zone
 *
 * @ORM\Table(name="zone", indexes={@ORM\Index(name="fk_zone_district1_idx", columns={"district_iddistrict"})})
 * @ORM\Entity
 */
class Zone
{
    /**
     * @var string
     *
     * @ORM\Column(name="zone_name", type="string", length=20, nullable=true)
     */
    private $zoneName;

    /**
     * @var integer
     *
     * @ORM\Column(name="idzone", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idzone;

    /**
     * @var \AppBundle\Entity\District
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\District")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="district_iddistrict", referencedColumnName="iddistrict")
     * })
     */
    private $districtdistrict;



    /**
     * Set zoneName
     *
     * @param string $zoneName
     * @return Zone
     */
    public function setZoneName($zoneName)
    {
        $this->zoneName = $zoneName;

        return $this;
    }

    /**
     * Get zoneName
     *
     * @return string 
     */
    public function getZoneName()
    {
        return $this->zoneName;
    }

    /**
     * Get idzone
     *
     * @return integer 
     */
    public function getIdzone()
    {
        return $this->idzone;
    }

    /**
     * Set districtdistrict
     *
     * @param \AppBundle\Entity\District $districtdistrict
     * @return Zone
     */
    public function setDistrictdistrict(\AppBundle\Entity\District $districtdistrict = null)
    {
        $this->districtdistrict = $districtdistrict;

        return $this;
    }

    /**
     * Get districtdistrict
     *
     * @return \AppBundle\Entity\District 
     */
    public function getDistrictdistrict()
    {
        return $this->districtdistrict;
    }
}
