<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Distribuidorlocal
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\DistribuidorlocalRepository")
 * @ORM\Table(name="distribuidorlocal")

 * @ORM\HasLifecycleCallbacks()
 */
class Distribuidorlocal
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
     * @ORM\Column(name="ciudad", type="string", length=255,nullable=false)
     */
    private $ciudad;

    /**
     * @var string
     * @ORM\Column(name="nombreveterinaria", type="string", length=255,nullable=false)
     */
    private $nombreveterinaria;


    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Provincia",inversedBy="distrubuidoreslocales", cascade={"persist"})
     */

    private $provincia;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;


    /**
     * @var text
     * @ORM\Column(name="personal", type="text",nullable=false)
     */
    private $personal;







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
     * Set ciudad
     *
     * @param string $ciudad
     * @return Distribuidorlocal
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set nombreveterinaria
     *
     * @param string $nombreveterinaria
     * @return Distribuidorlocal
     */
    public function setNombreveterinaria($nombreveterinaria)
    {
        $this->nombreveterinaria = $nombreveterinaria;

        return $this;
    }

    /**
     * Get nombreveterinaria
     *
     * @return string 
     */
    public function getNombreveterinaria()
    {
        return $this->nombreveterinaria;
    }

    /**
     * Set publico
     *
     * @param boolean $publico
     * @return Distribuidorlocal
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
     * Set personal
     *
     * @param string $personal
     * @return Distribuidorlocal
     */
    public function setPersonal($personal)
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * Get personal
     *
     * @return string 
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Set distrubuidoreslocal
     *
     * @param \GEMA\gemaBundle\Entity\Provincia $distrubuidoreslocal
     * @return Distribuidorlocal
     */
    public function setDistrubuidoreslocal(\GEMA\gemaBundle\Entity\Provincia $distrubuidoreslocal = null)
    {
        $this->distrubuidoreslocal = $distrubuidoreslocal;

        return $this;
    }

    /**
     * Get distrubuidoreslocal
     *
     * @return \GEMA\gemaBundle\Entity\Provincia 
     */
    public function getDistrubuidoreslocal()
    {
        return $this->distrubuidoreslocal;
    }

    /**
     * Set provincia
     *
     * @param \GEMA\gemaBundle\Entity\Provincia $provincia
     * @return Distribuidorlocal
     */
    public function setProvincia(\GEMA\gemaBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \GEMA\gemaBundle\Entity\Provincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
}
