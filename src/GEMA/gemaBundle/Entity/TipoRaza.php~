<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoRaza
 *
 * @ORM\Table(name="tiporaza")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\TipoRazaRepository")
 * @UniqueEntity(fields="tipo", message="Este tipo de raza ya existe")
 * @ORM\HasLifecycleCallbacks() 
 */
class TipoRaza
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
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Raza" , mappedBy="tiporaza", cascade={"persist"})
     */

    private $razas;



    public function __toString()
    {
        return $this->getTipo();
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
     * Set tipo
     *
     * @param string $tipo
     * @return TipoRaza
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
     * Constructor
     */
    public function __construct()
    {
        $this->razas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add razas
     *
     * @param \GEMA\gemaBundle\Entity\Raza $razas
     * @return TipoRaza
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
