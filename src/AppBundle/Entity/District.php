<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * District
 *
 * @ORM\Table(name="district")
 * @ORM\Entity
 */
class District
{
    /**
     * @var string
     *
     * @ORM\Column(name="district_name", type="string", length=20, nullable=true)
     */
    private $districtName;

    /**
     * @var integer
     *
     * @ORM\Column(name="iddistrict", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddistrict;



    /**
     * Set districtName
     *
     * @param string $districtName
     * @return District
     */
    public function setDistrictName($districtName)
    {
        $this->districtName = $districtName;

        return $this;
    }

    /**
     * Get districtName
     *
     * @return string 
     */
    public function getDistrictName()
    {
        return $this->districtName;
    }

    /**
     * Get iddistrict
     *
     * @return integer 
     */
    public function getIddistrict()
    {
        return $this->iddistrict;
    }
}
