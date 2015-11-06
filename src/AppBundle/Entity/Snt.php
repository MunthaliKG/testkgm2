<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Snt
 *
 * @ORM\Table(name="snt")
 * @ORM\Entity
 */
class Snt
{
    /**
     * @var string
     *
     * @ORM\Column(name="sfirst_name", type="string", length=25, nullable=false)
     */
    private $sfirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="slast_name", type="string", length=25, nullable=false)
     */
    private $slastName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="employment_number", type="string", length=10, nullable=false)
     */
    private $employmentNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="sinitials", type="string", length=25, nullable=false)
     */
    private $sinitials;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="s_dob", type="date", nullable=false)
     */
    private $s_dob;

    /**
     * @var string
     *
     * @ORM\Column(name="s_sex", type="string", nullable=false)
     */
    private $sSex;

     /**
     * @var string
     *
     * @ORM\Column(name="qualification", type="string", nullable=true)
     */
    private $qualification;

    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="simple_array", nullable=true)
     */
    private $speciality;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year_started", type="year", nullable=false)
     */
    private $yearStarted;

     /**
     * @var integer
     *
     * @ORM\Column(name="idsnt", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsnt;

    /**
     * Set sfirstName
     *
     * @param string $sfirstName
     * @return Snt
     */
    public function setSfirstName($sfirstName)
    {
        $this->sfirstName = $sfirstName;

        return $this;
    }

    /**
     * Get sfirstName
     *
     * @return string 
     */
    public function getSfirstName()
    {
        return $this->sfirstName;
    }
    /**
     * Set employmentNumber
     *
     * @param string $employmentNumber
     * @return Snt
     */
    public function setEmploymentNumber($employmentNumber)
    {
        $this->employmentNumber = $employmentNumber;

        return $this;
    }

    /**
     * Get employmentNumber
     *
     * @return string 
     */
    public function getEmploymentNumber()
    {
        return $this->employmentNumber;
    }

    /**
     * Set slastName
     *
     * @param string $slastName
     * @return Snt
     */
    public function setSlastName($slastName)
    {
        $this->slastName = $slastName;

        return $this;
    }

    /**
     * Get slastName
     *
     * @return string 
     */
    public function getSlastName()
    {
        return $this->slastName;
    }

    /**
     * Set sinitials
     *
     * @param string $sinitials
     * @return Snt
     */
    public function setSinitials($sinitials)
    {
        $this->sinitials = $sinitials;

        return $this;
    }

    /**
     * Get sinitials
     *
     * @return string 
     */
    public function getSinitials()
    {
        return $this->sinitials;
    }

    /**
     * Set sSex
     *
     * @param string $sSex
     * @return Snt
     */
    public function setSSex($sSex)
    {
        $this->sSex = $sSex;

        return $this;
    }
    /**
     * Set s_dob
     *
     * @param \DateTime $s_dob
     * @return Snt
     */
    public function setSdob($s_dob)
    {
        $this->s_dob = $s_dob;

        return $this;
    }

    /**
     * Get s_dob
     *
     * @return \DateTime 
     */
    public function getSdob()
    {
        return $this->s_dob;
    }
    /**
     * Get sSex
     *
     * @return string 
     */
    public function getSSex()
    {
        return $this->sSex;
    }
    /**
     * Set qualification
     *
     * @param string $qualification
     * @return Snt
     */
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;

        return $this;
    }

    /**
     * Get qualification
     *
     * @return string 
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set speciality
     *
     * @param string $speciality
     * @return Snt
     */
    public function setSpeciality($speciality)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality
     *
     * @return string 
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set yearStarted
     *
     * @param \DateTime $yearStarted
     * @return Snt
     */
    public function setYearStarted($yearStarted)
    {
        $this->yearStarted = $yearStarted;

        return $this;
    }

    /**
     * Get yearStarted
     *
     * @return \DateTime 
     */
    public function getYearStarted()
    {
        return $this->yearStarted;
    }

    /**
     * Get idsnt
     *
     * @return integer 
     */
    public function getIdsnt()
    {
        return $this->idsnt;
    }
}
