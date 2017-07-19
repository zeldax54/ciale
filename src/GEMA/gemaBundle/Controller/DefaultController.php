<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {




        return $this->render('gemaBundle:Default:index.html.twig', array(

                )
                );
    }

    public function loggedcheckAction() {

        $response = array("logged" => false);
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') || $securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $response["logged"] = true;
        }

        //
        return new \Symfony\Component\HttpFoundation\JsonResponse($response);
    }

}
