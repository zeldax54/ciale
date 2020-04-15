<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Metodopago
 *
 * @ORM\Table(name="metodopago")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\MetodopagoRepository")
 */
class Metodopago {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;   

     /**
     * @var integer
     *
     * @ORM\Column(name="dosismin", type="integer")
     */
    private $dosismin;

    /**
   * @var integer
   *
   * @ORM\Column(name="dosismax", type="integer")
   */
    private $dosismax;
   
     /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=200)
     */
      private $tipo;
    

     /**
     * @var integer
     *
     * @ORM\Column(name="descuentoporc", type="integer")
     */
    private $descuentoporc;

     /**
     * @var string
     *
     * @ORM\Column(name="descrip1", type="string",length=200,nullable=true)
     */
    private $descrip1;

     /**
     * @var string
     *
     * @ORM\Column(name="descrip2", type="string",length=200,nullable=true)
     */
    private $descrip2;

     /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Compra" , mappedBy="metodopago", cascade={"persist"})
     * @Exclude
     */
    private $compras;



   
    public function __toString(){
        return $this->getTipo().' Min:'.$this->getDosismin().' Max:'.$this->getDosismax();
    }
  


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
     * Set dosismin
     *
     * @param integer $dosismin
     * @return Metodopago
     */
    public function setDosismin($dosismin)
    {
        $this->dosismin = $dosismin;

        return $this;
    }

    /**
     * Get dosismin
     *
     * @return integer 
     */
    public function getDosismin()
    {
        return $this->dosismin;
    }

    /**
     * Set dosismax
     *
     * @param integer $dosismax
     * @return Metodopago
     */
    public function setDosismax($dosismax)
    {
        $this->dosismax = $dosismax;

        return $this;
    }

    /**
     * Get dosismax
     *
     * @return integer 
     */
    public function getDosismax()
    {
        return $this->dosismax;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Metodopago
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set descuentoporc
     *
     * @param integer $descuentoporc
     * @return Metodopago
     */
    public function setDescuentoporc($descuentoporc)
    {
        $this->descuentoporc = $descuentoporc;

        return $this;
    }

    /**
     * Get descuentoporc
     *
     * @return integer 
     */
    public function getDescuentoporc()
    {
        return $this->descuentoporc;
    }

    /**
     * Set descrip1
     *
     * @param integer $descrip1
     * @return Metodopago
     */
    public function setDescrip1($descrip1)
    {
        $this->descrip1 = $descrip1;

        return $this;
    }

    /**
     * Get descrip1
     *
     * @return integer 
     */
    public function getDescrip1()
    {
        return $this->descrip1;
    }

    /**
     * Set descrip2
     *
     * @param integer $descrip2
     * @return Metodopago
     */
    public function setDescrip2($descrip2)
    {
        $this->descrip2 = $descrip2;

        return $this;
    }

    /**
     * Get descrip2
     *
     * @return integer 
     */
    public function getDescrip2()
    {
        return $this->descrip2;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->compras = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add compras
     *
     * @param \GEMA\gemaBundle\Entity\Compra $compras
     * @return Metodopago
     */
    public function addCompra(\GEMA\gemaBundle\Entity\Compra $compras)
    {
        $this->compras[] = $compras;

        return $this;
    }

    /**
     * Remove compras
     *
     * @param \GEMA\gemaBundle\Entity\Compra $compras
     */
    public function removeCompra(\GEMA\gemaBundle\Entity\Compra $compras)
    {
        $this->compras->removeElement($compras);
    }

    /**
     * Get compras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompras()
    {
        return $this->compras;
    }
}
