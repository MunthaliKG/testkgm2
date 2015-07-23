<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Guardian
 *
 * @ORM\Table(name="guardian")
 * @ORM\Entity
 */
class Guardian
{
    /**
     * @var string
     *
     * @ORM\Column(name="gfirst_name", type="string", length=25, nullable=false)
     */
    private $gfirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="glast_name", type="string", length=25, nullable=false)
     */
    private $glastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gdob", type="date", nullable=false)
     */
    private $gdob;

    /**
     * @var string
     *
     * @ORM\Column(name="gsex", type="string", nullable=false)
     */
    private $gsex;

    /**
     * @var string
     *
     * @ORM\Column(name="gaddress", type="string", length=100, nullable=false)
     */
    private $gaddress;

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=20, nullable=true)
     */
    private $occupation;

    /**
     * @var string
     *
     * @ORM\Column(name="income_level", type="string", nullable=true)
     */
    private $incomeLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=11, nullable=false)
     */
    private $district;

    /**
     * @var integer
     *
     * @ORM\Column(name="idguardian", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idguardian;



    /**
     * Set gfirstName
     *
     * @param string $gfirstName
     * @return Guardian
     */
    public function setGfirstName($gfirstName)
    {
        $this->gfirstName = $gfirstName;

        return $this;
    }

    /**
     * Get gfirstName
     *
     * @return string 
     */
    public function getGfirstName()
    {
        return $this->gfirstName;
    }

    /**
     * Set glastName
     *
     * @param string $glastName
     * @return Guardian
     */
    public function setGlastName($glastName)
    {
        $this->glastName = $glastName;

        return $this;
    }

    /**
     * Get glastName
     *
     * @return string 
     */
    public function getGlastName()
    {
        return $this->glastName;
    }

    /**
     * Set gdob
     *
     * @param \DateTime $gdob
     * @return Guardian
     */
    public function setGdob($gdob)
    {
        $this->gdob = $gdob;

        return $this;
    }

    /**
     * Get gdob
     *
     * @return \DateTime 
     */
    public function getGdob()
    {
        return $this->gdob;
    }

    /**
     * Set gsex
     *
     * @param string $gsex
     * @return Guardian
     */
    public function setGsex($gsex)
    {
        $this->gsex = $gsex;

        return $this;
    }

    /**
     * Get gsex
     *
     * @return string 
     */
    public function getGsex()
    {
        return $this->gsex;
    }

    /**
     * Set gaddress
     *
     * @param string $gaddress
     * @return Guardian
     */
    public function setGaddress($gaddress)
    {
        $this->gaddress = $gaddress;

        return $this;
    }

    /**
     * Get gaddress
     *
     * @return string 
     */
    public function getGaddress()
    {
        return $this->gaddress;
    }

    /**
     * Set occupation
     *
     * @param string $occupation
     * @return Guardian
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * Get occupation
     *
     * @return string 
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * Set incomeLevel
     *
     * @param string $incomeLevel
     * @return Guardian
     */
    public function setIncomeLevel($incomeLevel)
    {
        $this->incomeLevel = $incomeLevel;

        return $this;
    }

    /**
     * Get incomeLevel
     *
     * @return string 
     */
    public function getIncomeLevel()
    {
        return $this->incomeLevel;
    }

    /**
     * Set district
     *
     * @param string $district
     * @return Guardian
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Get idguardian
     *
     * @return integer 
     */
    public function getIdguardian()
    {
        return $this->idguardian;
    }
}
