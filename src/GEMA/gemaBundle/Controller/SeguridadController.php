<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SeguridadController extends Controller {

//    public function indexAction($name)
//    {
//        return $this->render('gemaBundle:Default:index.html.twig', array('name' => $name));
//    }

    
    /**
     * @Template()
     */
    public function loginAction(Request $request) {


        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
        );
    }


    /**
     *
     */
    public function securityCheckAction() {
        // The security layer will intercept this request

    }


    public function logoutAction() {
        // The security layer will intercept this request
    }

}
