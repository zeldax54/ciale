<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Raza
 *
 * @ORM\Table(name="mapa")
 *  @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\MapaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Mapa
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Raza" , mappedBy="mapa", cascade={"persist"})
     */

    private $razas;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\MapaDatos" , mappedBy="mapa", cascade={"persist"})
     */

    private $mapadatos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mapadatos = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function __toString()
    {
        return $this->getNombre();
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
     * @return Mapa
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
     * Add mapadatos
     *
     * @param \GEMA\gemaBundle\Entity\MapaDatos $mapadatos
     * @return Mapa
     */
    public function addMapadato(\GEMA\gemaBundle\Entity\MapaDatos $mapadatos)
    {
        $this->mapadatos[] = $mapadatos;

        return $this;
    }

    /**
     * Remove mapadatos
     *
     * @param \GEMA\gemaBundle\Entity\MapaDatos $mapadatos
     */
    public function removeMapadato(\GEMA\gemaBundle\Entity\MapaDatos $mapadatos)
    {
        $this->mapadatos->removeElement($mapadatos);
    }

    /**
     * Get mapadatos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMapadatos()
    {
        return $this->mapadatos;
    }

    /**
     * Add razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     * @return Mapa
     */
    public function addRaza(\GEMA\gemaBundle\Entity\Raza $razas)
    {
        $this->razas[] = $razas;

        return $this;
    }

    /**
     * Remove razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     */
    public function removeRaza(\GEMA\gemaBundle\Entity\Raza $razas)
    {
        $this->razas->removeElement($razas);
    }

    /**
     * Get razas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRazas()
    {
        return $this->razas;
    }
}
