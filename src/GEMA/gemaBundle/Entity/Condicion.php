<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Condicion
 *
 * @ORM\Table(name="condicion")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\CondicionRepository")
 */
class Condicion {

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
     * @var string
     *
     * @ORM\Column(name="phpcode", type="string",length=255)
     */
    private $phpcode;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\ComportamientoCondicion" , mappedBy="condicionphp", cascade={"persist"})
     */

    private $comportamientocondiciones;





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comportamientocondiciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Condicion
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
     * Set phpcode
     *
     * @param string $phpcode
     * @return Condicion
     */
    public function setPhpcode($phpcode)
    {
        $this->phpcode = $phpcode;

        return $this;
    }

    /**
     * Get phpcode
     *
     * @return string 
     */
    public function getPhpcode()
    {
        return $this->phpcode;
    }

    /**
     * Add comportamientocondiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones
     * @return Condicion
     */
    public function addComportamientocondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones)
    {
        $this->comportamientocondiciones[] = $comportamientocondiciones;

        return $this;
    }

    /**
     * Remove comportamientocondiciones
     *
     * @param \GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones
     */
    public function removeComportamientocondicione(\GEMA\gemaBundle\Entity\ComportamientoCondicion $comportamientocondiciones)
    {
        $this->comportamientocondiciones->removeElement($comportamientocondiciones);
    }

    /**
     * Get comportamientocondiciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComportamientocondiciones()
    {
        return $this->comportamientocondiciones;
    }
}
