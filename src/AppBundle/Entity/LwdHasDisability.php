<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LwdHasDisability
 *
 * @ORM\Table(name="lwd_has_disability", indexes={@ORM\Index(name="fk_lwd_has_disability_disability1_idx", columns={"iddisability"}), @ORM\Index(name="fk_lwd_has_disability_lwd1_idx", columns={"idlwd"}), @ORM\Index(name="fk_lwd_has_disability_disability_has_level1_idx", columns={"idlevel", "iddisability"})})
 * @ORM\Entity
 */
class LwdHasDisability
{
    /**
     * @var simplearray
     *
     * @ORM\Column(name="assessed_by", type="simplearray", nullable=false)
     */
    private $assessedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_assessed", type="date", nullable=false)
     */
    private $dateAssessed;

    /**
     * @var simplearray
     *
     * @ORM\Column(name="identified_by", type="simplearray", nullable=false)
     */
    private $identifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="identification_date", type="date", nullable=false)
     */
    private $identificationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="case_description", type="string", length=150, nullable=true)
     */
    private $caseDescription;

    /**
     * @var \AppBundle\Entity\Disability
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Disability")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
     * })
     */
    private $iddisability;

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
     * @var \AppBundle\Entity\DisabilityHasLevel
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DisabilityHasLevel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlevel", referencedColumnName="idlevel"),
     *   @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
     * })
     */
    private $idlevel;



    /**
     * Set assessedBy
     *
     * @param \simplearray $assessedBy
     * @return LwdHasDisability
     */
    public function setAssessedBy(\simplearray $assessedBy)
    {
        $this->assessedBy = $assessedBy;

        return $this;
    }

    /**
     * Get assessedBy
     *
     * @return \simplearray 
     */
    public function getAssessedBy()
    {
        return $this->assessedBy;
    }

    /**
     * Set dateAssessed
     *
     * @param \DateTime $dateAssessed
     * @return LwdHasDisability
     */
    public function setDateAssessed($dateAssessed)
    {
        $this->dateAssessed = $dateAssessed;

        return $this;
    }

    /**
     * Get dateAssessed
     *
     * @return \DateTime 
     */
    public function getDateAssessed()
    {
        return $this->dateAssessed;
    }

    /**
     * Set identifiedBy
     *
     * @param \simplearray $identifiedBy
     * @return LwdHasDisability
     */
    public function setIdentifiedBy(\simplearray $identifiedBy)
    {
        $this->identifiedBy = $identifiedBy;

        return $this;
    }

    /**
     * Get identifiedBy
     *
     * @return \simplearray 
     */
    public function getIdentifiedBy()
    {
        return $this->identifiedBy;
    }

    /**
     * Set identificationDate
     *
     * @param \DateTime $identificationDate
     * @return LwdHasDisability
     */
    public function setIdentificationDate($identificationDate)
    {
        $this->identificationDate = $identificationDate;

        return $this;
    }

    /**
     * Get identificationDate
     *
     * @return \DateTime 
     */
    public function getIdentificationDate()
    {
        return $this->identificationDate;
    }

    /**
     * Set caseDescription
     *
     * @param string $caseDescription
     * @return LwdHasDisability
     */
    public function setCaseDescription($caseDescription)
    {
        $this->caseDescription = $caseDescription;

        return $this;
    }

    /**
     * Get caseDescription
     *
     * @return string 
     */
    public function getCaseDescription()
    {
        return $this->caseDescription;
    }

    /**
     * Set iddisability
     *
     * @param \AppBundle\Entity\Disability $iddisability
     * @return LwdHasDisability
     */
    public function setIddisability(\AppBundle\Entity\Disability $iddisability)
    {
        $this->iddisability = $iddisability;

        return $this;
    }

    /**
     * Get iddisability
     *
     * @return \AppBundle\Entity\Disability 
     */
    public function getIddisability()
    {
        return $this->iddisability;
    }

    /**
     * Set idlwd
     *
     * @param \AppBundle\Entity\Lwd $idlwd
     * @return LwdHasDisability
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

    /**
     * Set idlevel
     *
     * @param \AppBundle\Entity\DisabilityHasLevel $idlevel
     * @return LwdHasDisability
     */
    public function setIdlevel(\AppBundle\Entity\DisabilityHasLevel $idlevel = null)
    {
        $this->idlevel = $idlevel;

        return $this;
    }

    /**
     * Get idlevel
     *
     * @return \AppBundle\Entity\DisabilityHasLevel 
     */
    public function getIdlevel()
    {
        return $this->idlevel;
    }
}
