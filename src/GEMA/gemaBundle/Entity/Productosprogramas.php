<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Productosprogramas
 *
 * @ORM\Table(name="productosprogramas")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ProductosprogramasRepository")
 * @UniqueEntity(fields="nombre", message="Este elemento ya existe"))
 * @ORM\HasLifecycleCallbacks()
 */
class Productosprogramas
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var text
     * @ORM\Column(name="introduccion", type="text",nullable=true)
     */
    private $introduccion;

    /**
     * @var text
     * @ORM\Column(name="descripcion", type="text",nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;

    /**
     * @ORM\Column(name="guid", type="string",length=255 ,nullable=false)
     */
    private $guid;







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
     * @return Productosprogramas
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
     * Set introduccion
     *
     * @param string $introduccion
     * @return Productosprogramas
     */
    public function setIntroduccion($introduccion)
    {
        $this->introduccion = $introduccion;

        return $this;
    }

    /**
     * Get introduccion
     *
     * @return string 
     */
    public function getIntroduccion()
    {
        return $this->introduccion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Productosprogramas
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Productosprogramas
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get publico
     *
     * @return boolean 
     */
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return Productosprogramas
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Productosprogramas
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
}
