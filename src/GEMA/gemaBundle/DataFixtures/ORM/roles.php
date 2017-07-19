<?php

namespace GEMA\gemaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GEMA\gemaBundle\Entity\Rol;

class Roles extends AbstractFixture implements OrderedFixtureInterface {

    public function getOrder() {
        return 1;
    }

    public function load(ObjectManager $manager) {
        $roles = array(
            array('nombre'=>'ROLE_ADMINISTRADOR', 'descripcion'=>'Administrador'),
            array('nombre'=>'ROLE_MANTENIMIENTO', 'descripcion'=>'Responsable de Mantenimiento'),
            array('nombre'=>'ROLE_OPERACIONES', 'descripcion'=>'Responsable de Operaciones'),
            array('nombre'=>'ROLE_ALMACENERO', 'descripcion'=>'Almacenero'));
        
        foreach ($roles as $rol) {
            $entidad = new Rol;
            $entidad->setNombre($rol['nombre']);
            $entidad->setDescripcion($rol['descripcion']);
            $manager->persist($entidad);            
        }
        $manager->flush();
    }

}

?>
