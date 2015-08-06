<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisabilityHasLevel
 *
 * @ORM\Table(name="disability_has_level", indexes={@ORM\Index(name="fk_disability_has_level_disability1_idx", columns={"iddisability"}), @ORM\Index(name="fk_disability_has_level_level1_idx", columns={"idlevel"})})
 * @ORM\Entity
 */
class DisabilityHasLevel
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="iddisability", type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Disability")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
     * })
     */
    private $iddisability;

    /**
     * @var integer
     *
     * @ORM\Column(name="idlevel", type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Level")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idlevel", referencedColumnName="idlevel")
     * })
     */
    private $idlevel;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set iddisability
     *
     * @param integer $iddisability
     * @return DisabilityHasLevel
     */
    public function setIddisability($iddisability)
    {
        $this->iddisability = $iddisability;

        return $this;
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
     * Set idlevel
     *
     * @param integer $idlevel
     * @return DisabilityHasLevel
     */
    public function setIdlevel($idlevel)
    {
        $this->idlevel = $idlevel;

        return $this;
    }

    /**
     * Get idlevel
     *
     * @return integer 
     */
    public function getIdlevel()
    {
        return $this->idlevel;
    }
}
