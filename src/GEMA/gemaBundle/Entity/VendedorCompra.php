<?php

namespace GEMA\gemaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * VendedorCompra
 *
 * @ORM\Table(name="vendedorcompra")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\VendedorCompraRepository")
 */
class VendedorCompra {

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
     * @ORM\Column(name="nombre", type="string", length=200)
     */
    private $nombre;

     /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200)
     */
    private $email;

     /**
     * @var boolean
     *
     * @ORM\Column(name="deshabilitado", type="boolean")
     */
     private $deshabilitado;

       /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Compra" , mappedBy="vendedor", cascade={"persist"})
     */
    private $compras;

   

    public function __toString(){

        return $this->getNombre().' '.$this->getEmail();
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
     * @return VendedorCompra
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
     * Set email
     *
     * @param string $email
     * @return VendedorCompra
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
     * @return VendedorCompra
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

    

    /**
     * Set deshabilitado
     *
     * @param boolean $deshabilitado
     * @return VendedorCompra
     */
    public function setDeshabilitado($deshabilitado)
    {
        $this->deshabilitado = $deshabilitado;

        return $this;
    }

    /**
     * Get deshabilitado
     *
     * @return boolean 
     */
    public function getDeshabilitado()
    {
        return $this->deshabilitado;
    }
}
