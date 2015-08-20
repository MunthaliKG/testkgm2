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
     * @var integer
     *
     * @ORM\Column(name="idneed", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idneed;

   
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
}
