<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="MainBundle\Entity\ProductRepository")
 * @UniqueEntity("code")
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="num_formula_production", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $numFormulaProduction;

    /**
     * @var boolean
     *
     * @ORM\Column(name="crude_lar", type="boolean")
     */
    private $crudeLar;

    /**
     * @var string
     *
     * @ORM\Column(name="type_entry", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $typeEntry;


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
     * Set code
     *
     * @param string $code
     * @return Product
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set numFormulaProduction
     *
     * @param string $numFormulaProduction
     * @return Product
     */
    public function setNumFormulaProduction($numFormulaProduction)
    {
        $this->numFormulaProduction = $numFormulaProduction;

        return $this;
    }

    /**
     * Get numFormulaProduction
     *
     * @return string 
     */
    public function getNumFormulaProduction()
    {
        return $this->numFormulaProduction;
    }

    /**
     * Set crudeLar
     *
     * @param boolean $crudeLar
     * @return Product
     */
    public function setCrudeLar($crudeLar)
    {
        $this->crudeLar = $crudeLar;

        return $this;
    }

    /**
     * Get crudeLar
     *
     * @return boolean 
     */
    public function getCrudeLar()
    {
        return $this->crudeLar;
    }

    /**
     * Set typeEntry
     *
     * @param string $typeEntry
     * @return Product
     */
    public function setTypeEntry($typeEntry)
    {
        $this->typeEntry = $typeEntry;

        return $this;
    }

    /**
     * Get typeEntry
     *
     * @return string 
     */
    public function getTypeEntry()
    {
        return $this->typeEntry;
    }
}
