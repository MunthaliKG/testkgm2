<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisabilityCategory
 *
 * @ORM\Table(name="disability_category")
 * @ORM\Entity
 */
class DisabilityCategory
{
    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=50, nullable=true)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="category_description", type="string", length=50, nullable=true)
     */
    private $categoryDescription;

    /**
     * @var integer
     *
     * @ORM\Column(name="iddisability_category", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddisabilityCategory;



    /**
     * Set categoryName
     *
     * @param string $categoryName
     * @return DisabilityCategory
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set categoryDescription
     *
     * @param string $categoryDescription
     * @return DisabilityCategory
     */
    public function setCategoryDescription($categoryDescription)
    {
        $this->categoryDescription = $categoryDescription;

        return $this;
    }

    /**
     * Get categoryDescription
     *
     * @return string 
     */
    public function getCategoryDescription()
    {
        return $this->categoryDescription;
    }

    /**
     * Get iddisabilityCategory
     *
     * @return integer 
     */
    public function getIddisabilityCategory()
    {
        return $this->iddisabilityCategory;
    }
}
