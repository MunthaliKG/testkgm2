<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Need
 *
 * @ORM\Table(name="need")
 * @ORM\Entity
 */
class Need
{
    /**
     * @var string
     *
     * @ORM\Column(name="needname", type="string", length=45, nullable=false)
     */
    private $needname;

    /**
     * @var string
     *
     * @ORM\Column(name="need_type", type="string", nullable=false)
     */
    private $needType;

    /**
     * @var string
     *
     * @ORM\Column(name="quantifiable", type="string", nullable=true)
     */
    private $quantifiable;

    /**
     * @var integer
     *
     * @ORM\Column(name="idneed", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idneed;

    // *
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="AppBundle\Entity\School", mappedBy="idneed")
     
    // private $emiscode;

    // *
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="AppBundle\Entity\LwdHasDisability", mappedBy="idneed")
     
    // private $idlwd;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Disability", inversedBy="idneed")
     * @ORM\JoinTable(name="disability_has_need",
     *   joinColumns={
     *     @ORM\JoinColumn(name="idneed", referencedColumnName="idneed")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="iddisability", referencedColumnName="iddisability")
     *   }
     * )
     */
    private $iddisability;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emiscode = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idlwd = new \Doctrine\Common\Collections\ArrayCollection();
        $this->iddisability = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set needname
     *
     * @param string $needname
     * @return Need
     */
    public function setNeedname($needname)
    {
        $this->needname = $needname;

        return $this;
    }

    /**
     * Get needname
     *
     * @return string 
     */
    public function getNeedname()
    {
        return $this->needname;
    }

    /**
     * Set needType
     *
     * @param string $needType
     * @return Need
     */
    public function setNeedType($needType)
    {
        $this->needType = $needType;

        return $this;
    }

    /**
     * Get needType
     *
     * @return string 
     */
    public function getNeedType()
    {
        return $this->needType;
    }

    /**
     * Set quantifiable
     *
     * @param string $quantifiable
     * @return Need
     */
    public function setQuantifiable($quantifiable)
    {
        $this->quantifiable = $quantifiable;

        return $this;
    }

    /**
     * Get quantifiable
     *
     * @return string 
     */
    public function getQuantifiable()
    {
        return $this->quantifiable;
    }

    /**
     * Get idneed
     *
     * @return integer 
     */
    public function getIdneed()
    {
        return $this->idneed;
    }

    /**
     * Add emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     * @return Need
     */
    public function addEmiscode(\AppBundle\Entity\School $emiscode)
    {
        $this->emiscode[] = $emiscode;

        return $this;
    }

    /**
     * Remove emiscode
     *
     * @param \AppBundle\Entity\School $emiscode
     */
    public function removeEmiscode(\AppBundle\Entity\School $emiscode)
    {
        $this->emiscode->removeElement($emiscode);
    }

    /**
     * Get emiscode
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmiscode()
    {
        return $this->emiscode;
    }

    /**
     * Add idlwd
     *
     * @param \AppBundle\Entity\LwdHasDisability $idlwd
     * @return Need
     */
    public function addIdlwd(\AppBundle\Entity\LwdHasDisability $idlwd)
    {
        $this->idlwd[] = $idlwd;

        return $this;
    }

    /**
     * Remove idlwd
     *
     * @param \AppBundle\Entity\LwdHasDisability $idlwd
     */
    public function removeIdlwd(\AppBundle\Entity\LwdHasDisability $idlwd)
    {
        $this->idlwd->removeElement($idlwd);
    }

    /**
     * Get idlwd
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdlwd()
    {
        return $this->idlwd;
    }

    /**
     * Add iddisability
     *
     * @param \AppBundle\Entity\Disability $iddisability
     * @return Need
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
