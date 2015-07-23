<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LwdBelongsToSchool
 *
 * @ORM\Table(name="lwd_belongs_to_school", indexes={@ORM\Index(name="fk_lwd_has_school_lwd1_idx", columns={"idlwd"}), @ORM\Index(name="fk_lwd_has_school_school1_idx", columns={"emiscode"})})
 * @ORM\Entity
 */
class LwdBelongsToSchool
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
     * Set year
     *
     * @param \DateTime $year
     * @return LwdBelongsToSchool
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
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return LwdBelongsToSchool
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
     * @return LwdBelongsToSchool
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
