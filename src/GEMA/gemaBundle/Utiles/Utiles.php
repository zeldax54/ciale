<?php

namespace GEMA\gemaBundle\Utiles;

use Symfony\Component\DependencyInjection\ContainerAware;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use GEMA\gemaBundle\Entity\Traza;
use GEMA\gemaBundle\Entity\Persona;

class Utiles extends ContainerAware {

    public function paginar($current = 1, $rowCount = 1, Query $query = null) {
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $total = $paginator->count();
        $offset = 0;

        if ($rowCount >= 0 && $current > 0) {
            $offset = ($current - 1) * $rowCount;
            if ($offset > $total || $offset < 0) {
                $current = 1;
                $offset = 0;
            }
            $query
                    ->setFirstResult($offset)
                    ->setMaxResults($rowCount);
        }

        $entities = $query->getArrayResult();
        $result = array(
            "current" => intval($current),
            "rowCount" => intval($rowCount),
            "rows" => $entities,
            "total" => $total
        );
        return $result;
    }

    /**
     * Shortcut to return the request service.
     *
     * @return Request
     *
     * @deprecated Deprecated since version 2.4, to be removed in 3.0. Ask
     *             Symfony to inject the Request object into your controller
     *             method instead by type hinting it in the method's signature.
     */
    public function getRequest() {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine() {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser() {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return bool    true if the service id is defined, false otherwise
     */
    public function has($id) {
        return $this->container->has($id);
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function get($id) {
        return $this->container->get($id);
    }

    public function traza($accion) {
        $fecha = new \DateTime();
        $nivel = $this->container->get('security.context')->getToken()->getUser()->getUsername();
        $traza = new Traza();
        $traza->setNivel($nivel);
        $traza->setFecha($fecha);
        $traza->setAccion($accion);
        $em = $this->getDoctrine()->getManager();
        $em->persist($traza);
        $em->flush();
    }

    public function personaGasto(Persona $entity) {
        $salario = $entity->getSalario();
        $gastoDia = $salario / $this->container->getParameter('dias_laborales');
        $gastoHora = $salario / ($this->container->getParameter('horas_laborales') * $this->container->getParameter('dias_laborales'));
        $gastoMinuto = $salario / ($this->container->getParameter('horas_laborales') * $this->container->getParameter('dias_laborales') * $this->container->getParameter('minutos_trabajados_hora'));
        $entity->setGastoDia($gastoDia);
        $entity->setGastoHora($gastoHora);
        $entity->setGastoMinuto($gastoMinuto);
    }

}
