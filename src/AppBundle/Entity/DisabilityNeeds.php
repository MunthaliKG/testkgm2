<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisabilityNeeds
 *
 * @ORM\Table(name="disability_needs")
 * @ORM\Entity
 */
class DisabilityNeeds
{
    /**
     * @var integer
     *
     * @ORM\Column(name="iddisability_needs", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddisabilityNeeds;



    /**
     * Get iddisabilityNeeds
     *
     * @return integer 
     */
    public function getIddisabilityNeeds()
    {
        return $this->iddisabilityNeeds;
    }
}
