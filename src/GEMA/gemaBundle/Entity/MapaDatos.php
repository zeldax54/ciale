<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Raza
 *
 * @ORM\Table(name="mapadatos")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\MapaDatosRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class MapaDatos
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
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Mapa",inversedBy="mapaadatos", cascade={"persist"})
     */

    private $mapa;


    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;//Nombre del campo en el excel



    /**
     * @var string
     * @ORM\Column(name="posinExcel", type="integer")
     */
    private $posinExcel;//Posicion de la columna en el excel

    /**
     * @var string
     * @ORM\Column(name="comentario", type="string", length=255,nullable=true)
     */
    private $comentario;//Comentario



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
     * @return MapaDatos
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
     * Set posinExcel
     *
     * @param integer $posinExcel
     * @return MapaDatos
     */
    public function setPosinExcel($posinExcel)
    {
        $this->posinExcel = $posinExcel;

        return $this;
    }

    /**
     * Get posinExcel
     *
     * @return integer 
     */
    public function getPosinExcel()
    {
        return $this->posinExcel;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     * @return MapaDatos
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set mapa
     *
     * @param \GEMA\gemaBundle\Entity\Tabla $mapa
     * @return MapaDatos
     */
    public function setMapa(\GEMA\gemaBundle\Entity\Tabla $mapa = null)
    {
        $this->mapa = $mapa;

        return $this;
    }

    /**
     * Get mapa
     *
     * @return \GEMA\gemaBundle\Entity\Tabla 
     */
    public function getMapa()
    {
        return $this->mapa;
    }
}
