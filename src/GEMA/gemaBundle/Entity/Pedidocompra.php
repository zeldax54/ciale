<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pedidocompra
 *
 * @ORM\Table(name="pedidocompra")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\PedidocompraRepository")
 */
class Pedidocompra {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;   

       /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Pedidobase",inversedBy="pedidoscompra", cascade={"persist"})
     */
     private $pedidobase;

      /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
     private $cantidad;

      /**
     * @var integer
     *
     * @ORM\Column(name="precio", type="integer")
     */
    private $precio;

     
      /**
     * @var integer
     *
     * @ORM\Column(name="subtotal", type="integer")
     */
    private $subtotal;

        /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Compra",inversedBy="pedidos", cascade={"persist"})
     */
    private $compra;

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
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Pedidocompra
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set pedidobase
     *
     * @param \GEMA\gemaBundle\Entity\Pedidobase $pedidobase
     * @return Pedidocompra
     */
    public function setPedidobase(\GEMA\gemaBundle\Entity\Pedidobase $pedidobase = null)
    {
        $this->pedidobase = $pedidobase;

        return $this;
    }

    /**
     * Get pedidobase
     *
     * @return \GEMA\gemaBundle\Entity\Pedidobase 
     */
    public function getPedidobase()
    {
        return $this->pedidobase;
    }

    /**
     * Set compra
     *
     * @param \GEMA\gemaBundle\Entity\Compra $compra
     * @return Pedidocompra
     */
    public function setCompra(\GEMA\gemaBundle\Entity\Compra $compra = null)
    {
        $this->compra = $compra;

        return $this;
    }

    /**
     * Get compra
     *
     * @return \GEMA\gemaBundle\Entity\Compra 
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     * @return Pedidocompra
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set subtotal
     *
     * @param integer $subtotal
     * @return Pedidocompra
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal
     *
     * @return integer 
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }
}
