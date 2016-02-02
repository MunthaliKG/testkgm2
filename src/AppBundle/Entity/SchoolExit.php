<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolExit
 *
 * @ORM\Table(name="school_exit", indexes={@ORM\Index(name="fk_dropout_school1_idx", columns={"emiscode"}), @ORM\Index(name="fk_dropout_lwd1_idx", columns={"idlwd"})})
 * @ORM\Entity
 */
class SchoolExit
{
    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", nullable=false)
     */
    private $reason;

    /**
     * @var string
     *
     * @ORM\Column(name="other_reason", type="string", length=20, nullable=true)
     */
    private $otherReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_exit", type="date", nullable=true)
     */
    private $dateOfExit;

    /**
     * @var year
     *
     * @ORM\Column(name="year", type="year")
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
     * @var \AppBundle\Entity\Lwd
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Lwd")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlwd", referencedColumnName="idlwd")
     * })
     */
    private $idlwd;



    /**
     * Set reason
     *
     * @param string $reason
     * @return SchoolExit
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set otherReason
     *
     * @param string $otherReason
     * @return SchoolExit
     */
    public function setOtherReason($otherReason)
    {
        $this->otherReason = $otherReason;

        return $this;
    }

    /**
     * Get otherReason
     *
     * @return string 
     */
    public function getOtherReason()
    {
        return $this->otherReason;
    }

    /**
     * Set dateOfExit
     *
     * @param \DateTime $dateOfExit
     * @return SchoolExit
     */
    public function setDateOfExit($dateOfExit)
    {
        $this->dateOfExit = $dateOfExit;

        return $this;
    }

    /**
     * Get dateOfExit
     *
     * @return \DateTime 
     */
    public function getDateOfExit()
    {
        return $this->dateOfExit;
    }

    /**
     * Set year
     *
     * @param year $year
     * @return SchoolExit
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
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return SchoolExit
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
     * Set idlwd
     *
     * @param \AppBundle\Entity\Lwd $idlwd
     * @return SchoolExit
     */
    public function setIdlwd(\AppBundle\Entity\Lwd $idlwd)
    {
        $this->idlwd = $idlwd;

        return $this;
    }

    /**
     * Get idlwd
     *
     * @return \AppBundle\Entity\Lwd 
     */
    public function getIdlwd()
    {
        return $this->idlwd;
    }
}
