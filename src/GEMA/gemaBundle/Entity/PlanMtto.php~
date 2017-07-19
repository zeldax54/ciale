<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * PlanMtto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\PlanMttoRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class PlanMtto
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="ciclo", type="string", length=255, nullable=true)
     */

    private $ciclo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\TipoPrioridad",inversedBy="planesMtto", cascade={"persist"})
     */
    private $tipoPrioridad;


    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Activo",inversedBy="planMtto", cascade={"persist"})
     */
    private $activo;


    /**
     * @ORM\OneToMany(targetEntity="GEMA\gemaBundle\Entity\OrdenTrabajo" ,mappedBy="planMtto", cascade={"persist"})
     */
    protected $ordenesTrabajo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ordenesTrabajo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getNombre()." ";
    }

    /**
     * @ORM\PreRemove
     * Release all the children on remove
     */
    public function preRemove() {

        foreach ($this->ordenesTrabajo as $child) {
            $child->setPlanMtto(null);
        }
    }

    /**
     * @ORM\PrePersist
     * Give noActivo to the Activo
     */
    public function prePersist() {
        $activo = $this->getActivo();
        $activo->setFechaProximoMtto($this->getFecha());
        if ($activo->getFechaUltimoMtto() != null) {
            $hoy = new DateTime("today");
            $fecha = $this->getFecha();
            if ($fecha <= $hoy) {
                $activo->setFechaUltimoMtto($fecha);
            }
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->prePersist();
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
     * @return PlanMtto
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
     * Set ciclo
     *
     * @param string $ciclo
     * @return PlanMtto
     */
    public function setCiclo($ciclo)
    {
        $this->ciclo = $ciclo;

        return $this;
    }

    /**
     * Get ciclo
     *
     * @return string 
     */
    public function getCiclo()
    {
        return $this->ciclo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return PlanMtto
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set tipoPrioridad
     *
     * @param \GEMA\gemaBundle\Entity\TipoPrioridad $tipoPrioridad
     * @return PlanMtto
     */
    public function setTipoPrioridad(\GEMA\gemaBundle\Entity\TipoPrioridad $tipoPrioridad = null)
    {
        $this->tipoPrioridad = $tipoPrioridad;

        return $this;
    }

    /**
     * Get tipoPrioridad
     *
     * @return \GEMA\gemaBundle\Entity\TipoPrioridad 
     */
    public function getTipoPrioridad()
    {
        return $this->tipoPrioridad;
    }

    /**
     * Set activo
     *
     * @param \GEMA\gemaBundle\Entity\Activo $activo
     * @return PlanMtto
     */
    public function setActivo(\GEMA\gemaBundle\Entity\Activo $activo = null)
    {

        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return \GEMA\gemaBundle\Entity\Activo 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add ordenesTrabajo
     *
     * @param \GEMA\gemaBundle\Entity\OrdenTrabajo $ordenesTrabajo
     * @return PlanMtto
     */
    public function addOrdenesTrabajo(\GEMA\gemaBundle\Entity\OrdenTrabajo $ordenesTrabajo)
    {

        $ordenesTrabajo->setPlanMtto($this);
        $this->ordenesTrabajo[] = $ordenesTrabajo;

        return $this;
    }

    public function setOrdenesTrabajo(\GEMA\gemaBundle\Entity\OrdenTrabajo $ordenesTrabajo)
    {
        $ordenesTrabajo->setPlanMtto($this);
        $this->ordenesTrabajo[] = $ordenesTrabajo;
        return $this;
    }

    /**
     * Remove ordenesTrabajo
     *
     * @param \GEMA\gemaBundle\Entity\OrdenTrabajo $ordenesTrabajo
     */
    public function removeOrdenesTrabajo(\GEMA\gemaBundle\Entity\OrdenTrabajo $ordenesTrabajo)
    {
        $this->ordenesTrabajo->removeElement($ordenesTrabajo);
    }

    /**
     * Get ordenesTrabajo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrdenesTrabajo()
    {
        return $this->ordenesTrabajo;
    }
}
