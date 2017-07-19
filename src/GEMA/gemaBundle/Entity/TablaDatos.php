<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TablaDatos
 *
 * @ORM\Table(name="tabladatos")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\TablaDatosRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TablaDatos
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
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Tabla",inversedBy="tabladatos", cascade={"persist"})
     */

    private $tabla;


    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=10)
     */
    private $nombre;//Nombre del campo en el excel



    /**
     * @var string
     * @ORM\Column(name="posinExcel", type="integer")
     */
    private $posinExcel;//Posicion de la columna en el excel

    /**
     * @var string
     * @ORM\Column(name="verenpc", type="integer",nullable=true)
     */
    private $verenpc;//Ver en prueba comprimida


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
     * Set tabla
     *
     * @param \GEMA\gemaBundle\Entity\Tabla $tabla
     * @return TablaDatos
     */
    public function setTabla(\GEMA\gemaBundle\Entity\Tabla $tabla = null)
    {
        $this->tabla = $tabla;

        return $this;
    }

    /**
     * Get tabla
     *
     * @return \GEMA\gemaBundle\Entity\Tabla 
     */
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TablaDatos
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
     * Set tipoDato
     *
     * @param string $tipoDato
     * @return TablaDatos
     */
    public function setTipoDato($tipoDato)
    {
        $this->tipoDato = $tipoDato;

        return $this;
    }

    /**
     * Get tipoDato
     *
     * @return string 
     */
    public function getTipoDato()
    {
        return $this->tipoDato;
    }

    /**
     * Set posinExcel
     *
     * @param integer $posinExcel
     * @return TablaDatos
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
     * Set verenpc
     *
     * @param integer $verenpc
     * @return TablaDatos
     */
    public function setVerenpc($verenpc)
    {
        $this->verenpc = $verenpc;

        return $this;
    }

    /**
     * Get verenpc
     *
     * @return integer 
     */
    public function getVerenpc()
    {
        return $this->verenpc;
    }
}
