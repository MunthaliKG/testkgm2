<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolHasSnt
 *
 * @ORM\Table(name="school_has_snt", indexes={@ORM\Index(name="fk_school_has_snt_snt1_idx", columns={"idsnt"}), @ORM\Index(name="fk_school_has_snt_school1_idx", columns={"emiscode"})})
 * @ORM\Entity
 */
class SchoolHasSnt
{
    /**
     * @var string
     *
     * @ORM\Column(name="snt_type", type="string", nullable=false)
     */
    private $sntType;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_visits", type="integer", nullable=true)
     */
    private $noOfVisits;

    /**
     * @var year
     *
     * @ORM\Column(name="year", type="year")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $year;

    /**
     * @var \AppBundle\Entity\Snt
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Snt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idsnt", referencedColumnName="idsnt")
     * })
     */
    private $idsnt;

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
     * Set sntType
     *
     * @param string $sntType
     * @return SchoolHasSnt
     */
    public function setSntType($sntType)
    {
        $this->sntType = $sntType;

        return $this;
    }

    /**
     * Get sntType
     *
     * @return string 
     */
    public function getSntType()
    {
        return $this->sntType;
    }

    /**
     * Set noOfVisits
     *
     * @param integer $noOfVisits
     * @return SchoolHasSnt
     */
    public function setNoOfVisits($noOfVisits)
    {
        $this->noOfVisits = $noOfVisits;

        return $this;
    }

    /**
     * Get noOfVisits
     *
     * @return integer 
     */
    public function getNoOfVisits()
    {
        return $this->noOfVisits;
    }

    /**
     * Set year
     *
     * @param year $year
     * @return SchoolHasSnt
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
     * Set idsnt
     *
     * @param \AppBundle\Entity\Snt $idsnt
     * @return SchoolHasSnt
     */
    public function setIdsnt(\AppBundle\Entity\Snt $idsnt)
    {
        $this->idsnt = $idsnt;

        return $this;
    }

    /**
     * Get idsnt
     *
     * @return \AppBundle\Entity\Snt 
     */
    public function getIdsnt()
    {
        return $this->idsnt;
    }

    /**
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return SchoolHasSnt
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
