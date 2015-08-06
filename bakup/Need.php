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
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="available_in_rc", type="string", nullable=true)
     */
    private $availableInRc;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="idneed", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idneed;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\School", mappedBy="idneed")
     */
    private $emiscode;

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
     * Set type
     *
     * @param string $type
     * @return Need
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set availableInRc
     *
     * @param string $availableInRc
     * @return Need
     */
    public function setAvailableInRc($availableInRc)
    {
        $this->availableInRc = $availableInRc;

        return $this;
    }

    /**
     * Get availableInRc
     *
     * @return string 
     */
    public function getAvailableInRc()
    {
        return $this->availableInRc;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Need
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
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
