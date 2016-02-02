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

    /*
     * @var \AppBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlevel", referencedColumnName="idlevel"),
     * })
     */  
    private $idlevel;

    // *
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Need", inversedBy="idlwd")
    //  * @ORM\JoinTable(name="lwd_has_disability_has_need",
    //  *   joinColumns={
    //  *     @ORM\JoinColumn(name="idlwd", referencedColumnName="idlwd"),
    //  *     @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
    //  *   },
    //  *   inverseJoinColumns={
    //  *     @ORM\JoinColumn(name="idneed", referencedColumnName="idneed")
    //  *   }
    //  * )
     
    // private $idneed;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idneed = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \AppBundle\Entity\Level $idlevel
     * @return LwdHasDisability
     */
    public function setIdlevel(\AppBundle\Entity\Level $idlevel = null)
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

    /**
     * Add idneed
     *
     * @param \AppBundle\Entity\Need $idneed
     * @return LwdHasDisability
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
}
