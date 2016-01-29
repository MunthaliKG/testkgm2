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
     * @var boolean
     *
     * @ORM\Column(name="std", type="boolean", nullable=false)
     */
    private $std;

    /**
     * @var string
     *
     * @ORM\Column(name="distance_to_school", type="string", nullable=false)
     */
    private $distanceToSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="means_to_school", type="string", nullable=false)
     */
    private $meansToSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="other_means", type="string", length=25, nullable=true)
     */
    private $otherMeans;

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
     * Set std
     *
     * @param boolean $std
     * @return LwdBelongsToSchool
     */
    public function setStd($std)
    {
        $this->std = $std;

        return $this;
    }

    /**
     * Get std
     *
     * @return boolean 
     */
    public function getStd()
    {
        return $this->std;
    }

    /**
     * Set distanceToSchool
     *
     * @param string $distanceToSchool
     * @return LwdBelongsToSchool
     */
    public function setDistanceToSchool($distanceToSchool)
    {
        $this->distanceToSchool = $distanceToSchool;

        return $this;
    }

    /**
     * Get distanceToSchool
     *
     * @return string 
     */
    public function getDistanceToSchool()
    {
        return $this->distanceToSchool;
    }

    /**
     * Set meansToSchool
     *
     * @param string $meansToSchool
     * @return LwdBelongsToSchool
     */
    public function setMeansToSchool($meansToSchool)
    {
        $this->meansToSchool = $meansToSchool;

        return $this;
    }

    /**
     * Get meansToSchool
     *
     * @return string 
     */
    public function getMeansToSchool()
    {
        return $this->meansToSchool;
    }

    /**
     * Set otherMeans
     *
     * @param string $otherMeans
     * @return LwdBelongsToSchool
     */
    public function setOtherMeans($otherMeans)
    {
        $this->otherMeans = $otherMeans;

        return $this;
    }

    /**
     * Get otherMeans
     *
     * @return string 
     */
    public function getOtherMeans()
    {
        return $this->otherMeans;
    }

    /**
     * Set year
     *
     * @param year $year
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
