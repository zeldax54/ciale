<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use GEMA\gemaBundle\Entity\Boletin;
use GEMA\gemaBundle\Form\BoletinType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GEMA\gemaBundle\Helpers\MyHelper;

/**
 * SinglehtmlclientController controller.
 *
 */
class SinglehtmlclientController extends Controller
{

    public function gethtmlAction($nombre){

        $em = $this->getDoctrine()->getManager();
        $html = $em->getRepository('gemaBundle:Singlehtml')->findOneByNombre($nombre
        );

        return $this->render('gemaBundle:Page:html.html.twig', array(
            'html' => $html

        ));
    }
}
