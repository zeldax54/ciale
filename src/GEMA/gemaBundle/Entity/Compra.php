<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Compra
 *
 * @ORM\Table(name="compra")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CompraRepository")
 */
class Compra {

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
     * @ORM\Column(name="nombre", type="string",length=200)
     */
    private $nombre;

     /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string",length=200)
     */
    private $apellido;

      /**
     * @var string
     *
     * @ORM\Column(name="empresa", type="string",length=200)
     */
    private $empresa;

     /**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string",length=200)
     */
    private $localidad;

     /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string",length=200)
     */
    private $provincia;

     /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string",length=200)
     */
    private $telefono;

     /**
     * @var string
     *
     * @ORM\Column(name="email", type="string",length=200)
     */
    private $email;

    
     /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\VendedorCompra",inversedBy="compras", cascade={"persist"})
     */
    private $vendedor;

      /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Pedidocompra" , mappedBy="compra", cascade={"persist"})
     */
    private $pedidos;

       /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Premio",inversedBy="compras", cascade={"persist"})
     */
    private $premio;

      /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Metodopago",inversedBy="compras", cascade={"persist"})
     */
    private $metodopago;

    
    /**
     * @var float
     *
     * @ORM\Column(name="descuento", type="float")
     */
    private $descuento;

      /**
     * @var date
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Compra
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
     * Set apellido
     *
     * @param string $apellido
     * @return Compra
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     * @return Compra
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Compra
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Compra
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Compra
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Compra
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set descuento
     *
     * @param integer $descuento
     * @return Compra
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return integer 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set vendedor
     *
     * @param \GEMA\gemaBundle\Entity\VendedorCompra $vendedor
     * @return Compra
     */
    public function setVendedor(\GEMA\gemaBundle\Entity\VendedorCompra $vendedor = null)
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    /**
     * Get vendedor
     *
     * @return \GEMA\gemaBundle\Entity\VendedorCompra 
     */
    public function getVendedor()
    {
        return $this->vendedor;
    }

    /**
     * Add pedidos
     *
     * @param \GEMA\gemaBundle\Entity\Pedidocompra $pedidos
     * @return Compra
     */
    public function addPedido(\GEMA\gemaBundle\Entity\Pedidocompra $pedidos)
    {
        $this->pedidos[] = $pedidos;

        return $this;
    }

    /**
     * Remove pedidos
     *
     * @param \GEMA\gemaBundle\Entity\Pedidocompra $pedidos
     */
    public function removePedido(\GEMA\gemaBundle\Entity\Pedidocompra $pedidos)
    {
        $this->pedidos->removeElement($pedidos);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }

    /**
     * Set premio
     *
     * @param \GEMA\gemaBundle\Entity\Premio $premio
     * @return Compra
     */
    public function setPremio(\GEMA\gemaBundle\Entity\Premio $premio = null)
    {
        $this->premio = $premio;

        return $this;
    }

    /**
     * Get premio
     *
     * @return \GEMA\gemaBundle\Entity\Premio 
     */
    public function getPremio()
    {
        return $this->premio;
    }

    /**
     * Set metodopago
     *
     * @param \GEMA\gemaBundle\Entity\Metodopago $metodopago
     * @return Compra
     */
    public function setMetodopago(\GEMA\gemaBundle\Entity\Metodopago $metodopago = null)
    {
        $this->metodopago = $metodopago;

        return $this;
    }

    /**
     * Get metodopago
     *
     * @return \GEMA\gemaBundle\Entity\Metodopago 
     */
    public function getMetodopago()
    {
        return $this->metodopago;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Compra
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
