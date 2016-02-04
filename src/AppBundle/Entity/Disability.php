<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Disability
 *
 * @ORM\Table(name="disability", indexes={@ORM\Index(name="fk_disability_disability_category1_idx", columns={"iddisability_category"})})
 * @ORM\Entity
 */
class Disability
{
    /**
     * @var simple_array
     *
     * @ORM\Column(name="teacher_speciality_required", type="simple_array", nullable=false)
     */
    private $teacherSpecialityRequired;

    /**
     * @var string
     *
     * @ORM\Column(name="disability_name", type="string", length=50, nullable=false)
     */
    private $disabilityName;

    /**
     * @var string
     *
     * @ORM\Column(name="disability_description", type="string", length=500, nullable=false)
     */
    private $disabilityDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="general_category", type="string", nullable=true)
     */
    private $generalCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="iddisability", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddisability;

    /**
     * @var \AppBundle\Entity\DisabilityCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DisabilityCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddisability_category", referencedColumnName="iddisability_category")
     * })
     */
    private $iddisabilityCategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Need", mappedBy="iddisability")
     */
    private $idneed;

    // *
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Level", inversedBy="iddisability")
    //  * @ORM\JoinTable(name="disability_has_level",
    //  *   joinColumns={
    //  *     @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
    //  *   },
    //  *   inverseJoinColumns={
    //  *     @ORM\JoinColumn(name="idlevel", referencedColumnName="idlevel")
    //  *   }
    //  * )
     
    // private $idlevel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idneed = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idlevel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set teacherSpecialityRequired
     *
     * @param $teacherSpecialityRequired
     * @return Disability
     */
    public function setTeacherSpecialityRequired($teacherSpecialityRequired)
    {
        $this->teacherSpecialityRequired = $teacherSpecialityRequired;

        return $this;
    }

    /**
     * Get teacherSpecialityRequired
     *
     * @return \simplearray 
     */
    public function getTeacherSpecialityRequired()
    {
        return $this->teacherSpecialityRequired;
    }

    /**
     * Set disabilityName
     *
     * @param string $disabilityName
     * @return Disability
     */
    public function setDisabilityName($disabilityName)
    {
        $this->disabilityName = $disabilityName;

        return $this;
    }

    /**
     * Get disabilityName
     *
     * @return string 
     */
    public function getDisabilityName()
    {
        return $this->disabilityName;
    }

    /**
     * Set disabilityDescription
     *
     * @param string $disabilityDescription
     * @return Disability
     */
    public function setDisabilityDescription($disabilityDescription)
    {
        $this->disabilityDescription = $disabilityDescription;

        return $this;
    }

    /**
     * Get disabilityDescription
     *
     * @return string 
     */
    public function getDisabilityDescription()
    {
        return $this->disabilityDescription;
    }

    /**
     * Set generalCategory
     *
     * @param string $generalCategory
     * @return Disability
     */
    public function setGeneralCategory($generalCategory)
    {
        $this->generalCategory = $generalCategory;

        return $this;
    }

    /**
     * Get generalCategory
     *
     * @return string 
     */
    public function getGeneralCategory()
    {
        return $this->generalCategory;
    }

    /**
     * Get iddisability
     *
     * @return integer 
     */
    public function getIddisability()
    {
        return $this->iddisability;
    }

    /**
     * Set iddisabilityCategory
     *
     * @param \AppBundle\Entity\DisabilityCategory $iddisabilityCategory
     * @return Disability
     */
    public function setIddisabilityCategory(\AppBundle\Entity\DisabilityCategory $iddisabilityCategory = null)
    {
        $this->iddisabilityCategory = $iddisabilityCategory;

        return $this;
    }

    /**
     * Get iddisabilityCategory
     *
     * @return \AppBundle\Entity\DisabilityCategory 
     */
    public function getIddisabilityCategory()
    {
        return $this->iddisabilityCategory;
    }

    /**
     * Add idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     * @return Disability
     */
    public function addIdneed(\AppBundle\Entity\Need $idneed)
    {
        $this->idneed[] = $idneed;

        return $this;
    }

    /**
     * Remove idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     */
    public function removeIdneed(\AppBundle\Entity\Need $idneed)
    {
        $this->idneed->removeElement($idneed);
    }

    /**
     * Get idneed
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdneed()
    {
        return $this->idneed;
    }

    /**
     * Add idlevel
     *
     * @param \AppBundle\Entity\Level $idlevel
     * @return Disability
     */
    public function addIdlevel(\AppBundle\Entity\Level $idlevel)
    {
        $this->idlevel[] = $idlevel;

        return $this;
    }

    /**
     * Remove idlevel
     *
     * @param \AppBundle\Entity\Level $idlevel
     */
    public function removeIdlevel(\AppBundle\Entity\Level $idlevel)
    {
        $this->idlevel->removeElement($idlevel);
    }

    /**
     * Get idlevel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdlevel()
    {
        return $this->idlevel;
    }
}
