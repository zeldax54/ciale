<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as JMS;

/**
 * Accion
 *
 * @ORM\Table(name="premio")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\PremioRepository")
 */
class Premio {

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
     * @ORM\Column(name="nombre", type="string",length=100)
     */
    private $nombre;

      /**
      * @Exclude
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Ruleta",inversedBy="premios", cascade={"persist"})
     */
    private $ruleta;

     /**
      * @Exclude
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Compra" , mappedBy="premio", cascade={"persist"})
     */
    private $compras;


    public function __toString(){

     return $this->getRuleta().' '.$this->getNombre();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Ruleta
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set ruleta
     *
     * @param \GEMA\gemaBundle\Entity\Ruleta $ruleta
     * @return Premio
     */
    public function setRuleta(\GEMA\gemaBundle\Entity\Ruleta $ruleta = null)
    {
        $this->ruleta = $ruleta;

        return $this;
    }

    /**
     * Get ruleta
     *
     * @return \GEMA\gemaBundle\Entity\Ruleta 
     */
    public function getRuleta()
    {
        return $this->ruleta;
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
     * @return Premio
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
