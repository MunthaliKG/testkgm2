<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Snt
 *
 * @ORM\Table(name="snt", uniqueConstraints={@ORM\UniqueConstraint(name="employment_number_UNIQUE", columns={"employment_number"})})
 * @ORM\Entity
 */
class Snt
{
    /**
     * @var string
     *
     * @ORM\Column(name="sfirst_name", type="string", length=50, nullable=false)
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
     * @ORM\Column(name="s_sex", type="string", nullable=false)
     */
    private $sSex;

    /**
     * @var string
     *
     * @ORM\Column(name="qualification", type="string", nullable=false)
     */
    private $qualification;

    /**
     * @var string
     *
     * @ORM\Column(name="speciality", type="string", nullable=false)
     */
    private $speciality;

    /**
     * @var year
     *
     * @ORM\Column(name="year_started", type="year", nullable=false)
     */
    private $yearStarted;

    /**
     * @var string
     *
     * @ORM\Column(name="employment_number", type="string", length=20, nullable=false)
     */
    private $employmentNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="teacher_type", type="string", nullable=false)
     */
    private $teacherType;

    /**
     * @var string
     *
     * @ORM\Column(name="cpd_training", type="string", nullable=true)
     */
    private $cpdTraining;

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
     * @param year $yearStarted
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
     * @return year 
     */
    public function getYearStarted()
    {
        return $this->yearStarted;
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
     * Set teacherType
     *
     * @param string $teacherType
     * @return Snt
     */
    public function setTeacherType($teacherType)
    {
        $this->teacherType = $teacherType;

        return $this;
    }

    /**
     * Get teacherType
     *
     * @return string 
     */
    public function getTeacherType()
    {
        return $this->teacherType;
    }

    /**
     * Set cpdTraining
     *
     * @param string $cpdTraining
     * @return Snt
     */
    public function setCpdTraining($cpdTraining)
    {
        $this->cpdTraining = $cpdTraining;

        return $this;
    }

    /**
     * Get cpdTraining
     *
     * @return string 
     */
    public function getCpdTraining()
    {
        return $this->cpdTraining;
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
