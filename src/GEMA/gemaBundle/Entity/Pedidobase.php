<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Exclude;

/**
 * Pedidobase
 *
 * @ORM\Table(name="pedidobase")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\PedidobaseRepository")
 */
class Pedidobase {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;   

      /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Toro",inversedBy="pedidosbase", cascade={"persist"})
     */
    private $toro;

     /**
     * @var integer
     *
     * @ORM\Column(name="preciolista", type="integer")
     */
    private $preciolista;

     /**
     * @var integer
     *
     * @ORM\Column(name="preciopromo", type="integer")
     */
    private $preciopromo;

    
     /**
      * @Exclude
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Pedidocompra" , mappedBy="pedidobase", cascade={"persist"})
     */
    private $pedidoscompra;

    /**
     * @ORM\Column(name="ocultar", type="boolean", nullable=true)
     */
     private $ocultar;
   


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
     * Set preciolista
     *
     * @param integer $preciolista
     * @return Pedidobase
     */
    public function setPreciolista($preciolista)
    {
        $this->preciolista = $preciolista;

        return $this;
    }

    /**
     * Get preciolista
     *
     * @return integer 
     */
    public function getPreciolista()
    {
        return $this->preciolista;
    }

    /**
     * Set preciopromo
     *
     * @param integer $preciopromo
     * @return Pedidobase
     */
    public function setPreciopromo($preciopromo)
    {
        $this->preciopromo = $preciopromo;

        return $this;
    }

    /**
     * Get preciopromo
     *
     * @return integer 
     */
    public function getPreciopromo()
    {
        return $this->preciopromo;
    }

    /**
     * Set toro
     *
     * @param \GEMA\gemaBundle\Entity\Toro $toro
     * @return Pedidobase
     */
    public function setToro(\GEMA\gemaBundle\Entity\Toro $toro = null)
    {
        $this->toro = $toro;

        return $this;
    }

    /**
     * Get toro
     *
     * @return \GEMA\gemaBundle\Entity\Toro 
     */
    public function getToro()
    {
        return $this->toro;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidoscompra = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pedidoscompra
     *
     * @param \GEMA\gemaBundle\Entity\Pedidocompra $pedidoscompra
     * @return Pedidobase
     */
    public function addPedidoscompra(\GEMA\gemaBundle\Entity\Pedidocompra $pedidoscompra)
    {
        $this->pedidoscompra[] = $pedidoscompra;

        return $this;
    }

    /**
     * Remove pedidoscompra
     *
     * @param \GEMA\gemaBundle\Entity\Pedidocompra $pedidoscompra
     */
    public function removePedidoscompra(\GEMA\gemaBundle\Entity\Pedidocompra $pedidoscompra)
    {
        $this->pedidoscompra->removeElement($pedidoscompra);
    }

    /**
     * Get pedidoscompra
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidoscompra()
    {
        return $this->pedidoscompra;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Pedidobase
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set ocultar
     *
     * @param boolean $ocultar
     * @return Pedidobase
     */
    public function setOcultar($ocultar)
    {
        $this->ocultar = $ocultar;

        return $this;
    }

    /**
     * Get ocultar
     *
     * @return boolean 
     */
    public function getOcultar()
    {
        return $this->ocultar;
    }
}
