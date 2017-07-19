<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;




/**
 * Rol
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\BoletinMediaRepository")
 * @ORM\Table(name="boletinmedia")
 */
class BoletinMedia {

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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;


    /**
     * @var string
     *
     * @ORM\Column(name="fullpath", type="string", length=1250)
     */
    private $fullpath;


    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Boletin",inversedBy="medias", cascade={"persist"})
     */

    private $boletin;






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
     * Set titulo
     *
     * @param string $titulo
     * @return Boletin
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return BoletinMedia
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
     * Set fullpath
     *
     * @param string $fullpath
     * @return BoletinMedia
     */
    public function setFullpath($fullpath)
    {
        $this->fullpath = $fullpath;

        return $this;
    }

    /**
     * Get fullpath
     *
     * @return string 
     */
    public function getFullpath()
    {
        return $this->fullpath;
    }

    /**
     * Set boletin
     *
     * @param \GEMA\gemaBundle\Entity\Boletin $boletin
     * @return BoletinMedia
     */
    public function setBoletin(\GEMA\gemaBundle\Entity\Boletin $boletin = null)
    {
        $this->boletin = $boletin;

        return $this;
    }

    /**
     * Get boletin
     *
     * @return \GEMA\gemaBundle\Entity\Boletin 
     */
    public function getBoletin()
    {
        return $this->boletin;
    }
}
