<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Level
 *
 * @ORM\Table(name="level")
 * @ORM\Entity
 */
class Level
{
    /**
     * @var string
     *
     * @ORM\Column(name="level_name", type="string", length=12, nullable=false)
     */
    private $levelName;

    /**
     * @var integer
     *
     * @ORM\Column(name="idlevel", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlevel;

    // *
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Disability", mappedBy="idlevel")
     
    // private $iddisability;

    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->iddisability = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set levelName
     *
     * @param string $levelName
     * @return Level
     */
    public function setLevelName($levelName)
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * Get levelName
     *
     * @return string 
     */
    public function getLevelName()
    {
        return $this->levelName;
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

    /**
     * Add iddisability
     *
     * @param \AppBundle\Entity\Disability $iddisability
     * @return Level
     */
    public function addIddisability(\AppBundle\Entity\Disability $iddisability)
    {
        $this->iddisability[] = $iddisability;

        return $this;
    }

    /**
     * Remove iddisability
     *
     * @param \AppBundle\Entity\Disability $iddisability
     */
    public function removeIddisability(\AppBundle\Entity\Disability $iddisability)
    {
        $this->iddisability->removeElement($iddisability);
    }

    /**
     * Get iddisability
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIddisability()
    {
        return $this->iddisability;
    }
}
