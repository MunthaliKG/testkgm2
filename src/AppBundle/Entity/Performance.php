<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Performance
 *
 * @ORM\Table(name="performance", uniqueConstraints={@ORM\UniqueConstraint(name="rec_id_UNIQUE", columns={"rec_id"})}, indexes={@ORM\Index(name="fk_performance_lwd1_idx", columns={"idlwd"}), @ORM\Index(name="fk_performance_school1_idx", columns={"emiscode"})})
 * @ORM\Entity
 */
class Performance
{
    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", nullable=true)
     */
    private $grade;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="teachercomment", type="string", length=45, nullable=true)
     */
    private $teachercomment;

    /**
     * @var integer
     *
     * @ORM\Column(name="rec_id", type="bigint", nullable=false)
     */
    private $recId;

    /**
     * @var integer
     *
     * @ORM\Column(name="std", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $std;

    /**
     * @var year
     *
     * @ORM\Column(name="year", type="year")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="term", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $term;

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
     * Set grade
     *
     * @param string $grade
     * @return Performance
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Performance
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set teachercomment
     *
     * @param string $teachercomment
     * @return Performance
     */
    public function setTeachercomment($teachercomment)
    {
        $this->teachercomment = $teachercomment;

        return $this;
    }

    /**
     * Get teachercomment
     *
     * @return string 
     */
    public function getTeachercomment()
    {
        return $this->teachercomment;
    }

    /**
     * Set recId
     *
     * @param integer $recId
     * @return Performance
     */
    public function setRecId($recId)
    {
        $this->recId = $recId;

        return $this;
    }

    /**
     * Get recId
     *
     * @return integer 
     */
    public function getRecId()
    {
        return $this->recId;
    }

    /**
     * Set std
     *
     * @param integer $std
     * @return Performance
     */
    public function setStd($std)
    {
        $this->std = $std;

        return $this;
    }

    /**
     * Get std
     *
     * @return integer 
     */
    public function getStd()
    {
        return $this->std;
    }

    /**
     * Set year
     *
     * @param year $year
     * @return Performance
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
     * Set term
     *
     * @param integer $term
     * @return Performance
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return integer 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return Performance
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
     * @return Performance
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
