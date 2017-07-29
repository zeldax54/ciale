<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Razafather
 *
 * @ORM\Table(name="razafather")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\RazafatherRepository")
 * @UniqueEntity(fields="nombre", message="Este raza ya existe")
 * @ORM\HasLifecycleCallbacks() 
 */
class Razafather
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
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Raza" , mappedBy="father", cascade={"persist"})
     */

    private $razas;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->razas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Razafather
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
     * Add razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     * @return Razafather
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

    public function __toString()
    {
        return $this->getNombre();
    }
}
