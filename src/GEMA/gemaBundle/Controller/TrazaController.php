<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Traza controller.
 *
 */
class TrazaController extends Controller {

    /**
     * Lists all Traza entities.
     *
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Traza');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Traza')->findAll();
        }
        $accion = 'Ver Registro de Trazas';
        $this->get("gema.utiles")->traza($accion);
        return $this->render('gemaBundle:Traza:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Traza entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Traza')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontro la Traza solicitada.');
        }

        return $this->render('gemaBundle:Traza:show.html.twig', array(
                    'entity' => $entity,
        ));
    }

}
