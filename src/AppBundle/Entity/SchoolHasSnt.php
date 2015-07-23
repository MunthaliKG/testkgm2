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
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date")
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
     * Set year
     *
     * @param \DateTime $year
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
     * @return \DateTime 
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
