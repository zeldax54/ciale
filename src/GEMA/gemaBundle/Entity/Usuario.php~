<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="GEMA\gemaBundle\Entity\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks() 
 */
class Usuario  implements UserInterface {



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
     * @Assert\Length(min = 6)
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="Usuario", type="string", length=255)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="GEMA\gemaBundle\Entity\Rol",inversedBy="usuarios", cascade={"persist"}) 
     */
    protected $roles;



    /**
     * @var string
     *
     * @ORM\Column(name="Salt", type="string", length=255)
     */
    private $salt;

    public function eraseCredentials() {
        
    }

    /**
     * Get password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->getUsuario();
    }

    public function __toString() {
        return $this->getUsername();
    }

    public function getRoles() {
        return $this->roles != null ? array($this->roles->getNombre()) : "";
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Usuario
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Add personas
     *
     * @param \GEMA\gemaBundle\Entity\Persona $personas
     * @return Usuario
     */
//    public function addPersona(\GEMA\gemaBundle\Entity\Persona $personas) {
//        $this->personas[] = $personas;
//
//        return $this;
//    }

    /**
     * Remove personas
     *
     * @param \GEMA\gemaBundle\Entity\Persona $personas
     */
//    public function removePersona(\GEMA\gemaBundle\Entity\Persona $personas) {
//        $this->personas->removeElement($personas);
//   }
//
//    /**
//     * Get personas
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getPersonas() {
//        return $this->personas;
//   }

    /**
     * Set roles
     *
     * @param \GEMA\gemaBundle\Entity\Rol $roles
     * @return Usuario
     */
    public function setRoles(\GEMA\gemaBundle\Entity\Rol $roles = null) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @ORM\PreRemove
     * Release all the children on remove
     */
    public function preRemove() {
        foreach ($this->personas as $child) {
            $child->setUsuario(null);
        }
//        $this->container->get("gema.utiles")->traza("Eliminada entidad Usuario Id: " . $this->getId() . " Usuario:" . $this->getUsername());
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

}
