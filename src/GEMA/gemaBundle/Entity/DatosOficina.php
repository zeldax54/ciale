<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Distribuidor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\DatosOficinaRepository")
 * @ORM\Table(name="datosoficina")
 * @UniqueEntity(fields="datosoficina", message="Estos datos ya existe")
 * @ORM\HasLifecycleCallbacks()
 */
class DatosOficina
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
     * @var text
     * @ORM\Column(name="datosoficina", type="text",nullable=false)
     */
    private $datosoficina;

    /**
     * @var text
     * @ORM\Column(name="email", type="text",nullable=true)
     */
    private $email;

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
     * Set datosoficina
     *
     * @param string $datosoficina
     * @return DatosOficina
     */
    public function setDatosoficina($datosoficina)
    {
        $this->datosoficina = $datosoficina;

        return $this;
    }

    /**
     * Get datosoficina
     *
     * @return string 
     */
    public function getDatosoficina()
    {
        return $this->datosoficina;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return DatosOficina
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}
