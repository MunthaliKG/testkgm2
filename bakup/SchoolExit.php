<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolExit
 *
 * @ORM\Table(name="school_exit", indexes={@ORM\Index(name="fk_dropout_school1_idx", columns={"school_emiscode"}), @ORM\Index(name="fk_dropout_lwd1_idx", columns={"lwd_idlwd"})})
 * @ORM\Entity
 */
class SchoolExit
{
    /**
     * @var simplearray
     *
     * @ORM\Column(name="reason", type="simplearray", nullable=false)
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
     *   @ORM\JoinColumn(name="school_emiscode", referencedColumnName="emiscode")
     * })
     */
    private $schoolEmiscode;

    /**
     * @var \AppBundle\Entity\Lwd
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Lwd")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lwd_idlwd", referencedColumnName="idlwd")
     * })
     */
    private $lwdlwd;



    /**
     * Set reason
     *
     * @param \simplearray $reason
     * @return SchoolExit
     */
    public function setReason(\simplearray $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return \simplearray 
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
     * Set year
     *
     * @param \DateTime $year
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
     * @return \DateTime 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set schoolEmiscode
     *
     * @param \AppBundle\Entity\School $schoolEmiscode
     * @return SchoolExit
     */
    public function setSchoolEmiscode(\AppBundle\Entity\School $schoolEmiscode)
    {
        $this->schoolEmiscode = $schoolEmiscode;

        return $this;
    }

    /**
     * Get schoolEmiscode
     *
     * @return \AppBundle\Entity\School 
     */
    public function getSchoolEmiscode()
    {
        return $this->schoolEmiscode;
    }

    /**
     * Set lwdlwd
     *
     * @param \AppBundle\Entity\Lwd $lwdlwd
     * @return SchoolExit
     */
    public function setLwdlwd(\AppBundle\Entity\Lwd $lwdlwd)
    {
        $this->lwdlwd = $lwdlwd;

        return $this;
    }

    /**
     * Get lwdlwd
     *
     * @return \AppBundle\Entity\Lwd 
     */
    public function getLwdlwd()
    {
        return $this->lwdlwd;
    }
}
