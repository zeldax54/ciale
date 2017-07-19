<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Provincia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\ProvinciaRepository")
 * @ORM\Table(name="provincia")
 * @UniqueEntity(fields="nombre", message="Esta provincia ya existe")
 * @ORM\HasLifecycleCallbacks()
 */
class Provincia
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
     * @ORM\Column(name="nombre", type="string", length=255,nullable=true)
     */
    private $nombre;

    /**
     * @ORM\Column(name="publico", type="boolean", nullable=true)
     */
    private $publico;

    /**
     * @ORM\Column(name="casacentral", type="boolean", nullable=true)
     */
    private $casacentral;

    /**
     * @ORM\Column(name="codigo", type="string",length=255 ,nullable=false)
     */
    private $codigo;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Staff" , mappedBy="provincia", cascade={"persist"})
     */

    private $staffs;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Vendedor" , mappedBy="provincia", cascade={"persist"})
     */

    private $vendedores;

    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\Distribuidorlocal" , mappedBy="provincia", cascade={"persist"})
     */

     private $distrubuidoreslocales;


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
     * @return Provincia
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
     * Set publico
     *
     * @param boolean $publico
     * @return Provincia
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
     * Set casacentral
     *
     * @param boolean $casacentral
     * @return Provincia
     */
    public function setCasacentral($casacentral)
    {
        $this->casacentral = $casacentral;

        return $this;
    }

    /**
     * Get casacentral
     *
     * @return boolean 
     */
    public function getCasacentral()
    {
        return $this->casacentral;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Provincia
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->staffs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add staffs
     *
     * @param \GEMA\gemaBundle\Entity\Staff $staffs
     * @return Provincia
     */
    public function addStaff(\GEMA\gemaBundle\Entity\Staff $staffs)
    {
        $this->staffs[] = $staffs;

        return $this;
    }

    /**
     * Remove staffs
     *
     * @param \GEMA\gemaBundle\Entity\Staff $staffs
     */
    public function removeStaff(\GEMA\gemaBundle\Entity\Staff $staffs)
    {
        $this->staffs->removeElement($staffs);
    }

    /**
     * Get staffs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStaffs()
    {
        return $this->staffs;
    }

    /**
     * Add vendedores
     *
     * @param \GEMA\gemaBundle\Entity\Vendedor $vendedores
     * @return Provincia
     */
    public function addVendedore(\GEMA\gemaBundle\Entity\Vendedor $vendedores)
    {
        $this->vendedores[] = $vendedores;

        return $this;
    }

    /**
     * Remove vendedores
     *
     * @param \GEMA\gemaBundle\Entity\Vendedor $vendedores
     */
    public function removeVendedore(\GEMA\gemaBundle\Entity\Vendedor $vendedores)
    {
        $this->vendedores->removeElement($vendedores);
    }

    /**
     * Get vendedores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVendedores()
    {
        return $this->vendedores;
    }

    /**
     * Add distrubuidoreslocales
     *
     * @param \GEMA\gemaBundle\Entity\Distribuidorlocal $distrubuidoreslocales
     * @return Provincia
     */
    public function addDistrubuidoreslocale(\GEMA\gemaBundle\Entity\Distribuidorlocal $distrubuidoreslocales)
    {
        $this->distrubuidoreslocales[] = $distrubuidoreslocales;

        return $this;
    }

    /**
     * Remove distrubuidoreslocales
     *
     * @param \GEMA\gemaBundle\Entity\Distribuidorlocal $distrubuidoreslocales
     */
    public function removeDistrubuidoreslocale(\GEMA\gemaBundle\Entity\Distribuidorlocal $distrubuidoreslocales)
    {
        $this->distrubuidoreslocales->removeElement($distrubuidoreslocales);
    }

    /**
     * Get distrubuidoreslocales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDistrubuidoreslocales()
    {
        return $this->distrubuidoreslocales;
    }
}
