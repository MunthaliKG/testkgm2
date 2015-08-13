<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisabilityHasLevel
 *
 * @ORM\Table(name="disability_has_level", indexes={@ORM\Index(name="fk_disability_has_level_disability1_idx", columns={"iddisability"}), @ORM\Index(name="fk_disability_has_level_level1_idx", columns={"idlevel"}), @ORM\Index(name="fk_lwd_has_disability_disability_has_level1", columns={"idlevel", "iddisability"})})
 * @ORM\Entity
 */
class DisabilityHasLevel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="iddisability_has_level", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddisabilityHasLevel;

    /**
     * @var \AppBundle\Entity\Level
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlevel", referencedColumnName="idlevel")
     * })
     */
    private $idlevel;

    /**
     * @var \AppBundle\Entity\Disability
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disability")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
     * })
     */
    private $iddisability;



    /**
     * Get iddisabilityHasLevel
     *
     * @return integer 
     */
    public function getIddisabilityHasLevel()
    {
        return $this->iddisabilityHasLevel;
    }

    /**
     * Set idlevel
     *
     * @param \AppBundle\Entity\Level $idlevel
     * @return DisabilityHasLevel
     */
    public function setIdlevel(\AppBundle\Entity\Level $idlevel = null)
    {
        $this->idlevel = $idlevel;

        return $this;
    }

    /**
     * Get idlevel
     *
     * @return \AppBundle\Entity\Level 
     */
    public function getIdlevel()
    {
        return $this->idlevel;
    }

    /**
     * Set iddisability
     *
     * @param \AppBundle\Entity\Disability $iddisability
     * @return DisabilityHasLevel
     */
    public function setIddisability(\AppBundle\Entity\Disability $iddisability = null)
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
}
